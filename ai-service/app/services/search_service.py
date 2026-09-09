import json
from app.services.llm_client import llm_client
from app.models.requests import SearchRequest
from app.models.responses import SearchResponse
from app.prompts.templates import SEARCH_SYSTEM_PROMPT, SEARCH_USER_PROMPT

class SearchService:
    async def search_tasks(self, request: SearchRequest) -> SearchResponse:
        if not request.tasks:
            return SearchResponse(results=[])
            
        tasks_json = json.dumps([t.model_dump() for t in request.tasks], indent=2)
        user_prompt = SEARCH_USER_PROMPT.format(
            query=request.query,
            tasks_json=tasks_json
        )
        
        response = await llm_client.generate_structured(
            system_prompt=SEARCH_SYSTEM_PROMPT,
            user_prompt=user_prompt,
            response_model=SearchResponse
        )
        
        # Sort results by relevance score
        response.results.sort(key=lambda x: x.relevance_score, reverse=True)
        return response

search_service = SearchService()
