import json
from app.services.llm_client import llm_client
from app.models.requests import ProjectSummaryRequest
from app.models.responses import ProjectSummaryResponse
from app.prompts.templates import PROJECT_SUMMARY_SYSTEM_PROMPT, PROJECT_SUMMARY_USER_PROMPT

class SummarizerService:
    async def generate_summary(self, request: ProjectSummaryRequest) -> ProjectSummaryResponse:
        tasks_json = json.dumps([t.model_dump() for t in request.tasks], indent=2)
        user_prompt = PROJECT_SUMMARY_USER_PROMPT.format(
            tasks_json=tasks_json,
            recent_activity=request.recent_activity
        )
        return await llm_client.generate_structured(
            system_prompt=PROJECT_SUMMARY_SYSTEM_PROMPT,
            user_prompt=user_prompt,
            response_model=ProjectSummaryResponse
        )

summarizer_service = SummarizerService()
