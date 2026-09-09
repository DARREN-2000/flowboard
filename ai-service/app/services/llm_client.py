import json
import logging
from typing import Type, TypeVar, Any, Dict
from pydantic import BaseModel
from structlog import get_logger
from tenacity import retry, stop_after_attempt, wait_exponential

import openai
from openai import AsyncOpenAI
import anthropic

from app.config import settings
from app.exceptions import LLMProviderError

logger = get_logger(__name__)

T = TypeVar('T', bound=BaseModel)

class LLMClient:
    def __init__(self):
        self.openai_client = AsyncOpenAI(api_key=settings.openai_api_key) if settings.openai_api_key else None
        self.anthropic_client = anthropic.AsyncAnthropic(api_key=settings.anthropic_api_key) if settings.anthropic_api_key else None

    @retry(stop=stop_after_attempt(3), wait=wait_exponential(multiplier=1, min=2, max=10))
    async def generate_structured(self, system_prompt: str, user_prompt: str, response_model: Type[T]) -> T:
        if settings.use_mock_llm or (not self.openai_client and not self.anthropic_client):
            logger.info("using_mock_llm", model=response_model.__name__)
            return self._mock_response(response_model)

        if self.openai_client:
            try:
                logger.info("calling_openai")
                completion = await self.openai_client.beta.chat.completions.parse(
                    model="gpt-4o-mini",
                    messages=[
                        {"role": "system", "content": system_prompt},
                        {"role": "user", "content": user_prompt}
                    ],
                    response_format=response_model,
                    temperature=0.2,
                    timeout=30.0
                )
                return completion.choices[0].message.parsed
            except Exception as e:
                logger.error("openai_call_failed", error=str(e))
                if self.anthropic_client:
                    logger.info("falling_back_to_anthropic")
                    return await self._call_anthropic(system_prompt, user_prompt, response_model)
                raise LLMProviderError(f"OpenAI call failed: {str(e)}", status_code=502)

        elif self.anthropic_client:
            return await self._call_anthropic(system_prompt, user_prompt, response_model)
            
        raise LLMProviderError("No LLM client configured and mock mode is off", status_code=500)

    async def _call_anthropic(self, system_prompt: str, user_prompt: str, response_model: Type[T]) -> T:
        try:
            logger.info("calling_anthropic")
            
            schema = response_model.model_json_schema()
            
            anthropic_system_prompt = f"{system_prompt}\n\nRespond ONLY with a valid JSON object matching this schema:\n{json.dumps(schema)}"
            
            message = await self.anthropic_client.messages.create(
                model="claude-3-haiku-20240307",
                max_tokens=2048,
                temperature=0.2,
                system=anthropic_system_prompt,
                messages=[
                    {"role": "user", "content": user_prompt}
                ]
            )
            
            response_text = message.content[0].text
            try:
                # Basic cleanup to ensure we get json
                start = response_text.find("{")
                end = response_text.rfind("}") + 1
                json_str = response_text[start:end]
                data = json.loads(json_str)
                return response_model(**data)
            except (json.JSONDecodeError, ValueError) as e:
                logger.error("anthropic_json_parse_error", error=str(e), response=response_text)
                raise LLMProviderError(f"Failed to parse Anthropic response as JSON: {str(e)}", status_code=502)
                
        except Exception as e:
            logger.error("anthropic_call_failed", error=str(e))
            raise LLMProviderError(f"Anthropic call failed: {str(e)}", status_code=502)

    def _mock_response(self, response_model: Type[T]) -> T:
        """Provide plausible fake data for development mode based on the requested model."""
        model_name = response_model.__name__
        if model_name == "GenerateTasksResponse":
            return response_model(
                tasks=[
                    {"title": "Setup database schema", "description": "Create initial tables for users and projects", "priority": "High"},
                    {"title": "Implement auth middleware", "description": "Add JWT verification", "priority": "Medium"}
                ]
            )
        elif model_name == "DecomposeTaskResponse":
            return response_model(
                subtasks=[
                    {"title": "Write unit tests", "description": "Ensure 80% coverage"},
                    {"title": "Implement core logic", "description": "Follow the spec"}
                ]
            )
        elif model_name == "ProjectSummaryResponse":
            return response_model(
                completion_percentage=65,
                blocked_items=["Waiting for API key from DevOps"],
                recent_completions=["Setup CI/CD pipeline", "User auth endpoint"],
                priority_suggestions=["Unblock DevOps task", "Finish frontend integration"]
            )
        elif model_name == "SearchResponse":
            return response_model(
                results=[
                    {"task_id": "mock-task-1", "relevance_score": 0.95, "reasoning": "Direct match to query terms"},
                    {"task_id": "mock-task-2", "relevance_score": 0.45, "reasoning": "Partial conceptual match"}
                ]
            )
        
        # Fallback
        return response_model()

llm_client = LLMClient()
