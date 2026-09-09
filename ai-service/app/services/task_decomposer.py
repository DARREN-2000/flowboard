from app.services.llm_client import llm_client
from app.models.requests import DecomposeTaskRequest
from app.models.responses import DecomposeTaskResponse
from app.prompts.templates import DECOMPOSE_TASK_SYSTEM_PROMPT, DECOMPOSE_TASK_USER_PROMPT

class TaskDecomposerService:
    async def decompose_task(self, request: DecomposeTaskRequest) -> DecomposeTaskResponse:
        user_prompt = DECOMPOSE_TASK_USER_PROMPT.format(
            title=request.title,
            description=request.description,
            context=request.context or "No additional context."
        )
        return await llm_client.generate_structured(
            system_prompt=DECOMPOSE_TASK_SYSTEM_PROMPT,
            user_prompt=user_prompt,
            response_model=DecomposeTaskResponse
        )

task_decomposer_service = TaskDecomposerService()
