from app.services.llm_client import llm_client
from app.models.requests import GenerateTasksRequest
from app.models.responses import GenerateTasksResponse
from app.prompts.templates import GENERATE_TASKS_SYSTEM_PROMPT, GENERATE_TASKS_USER_PROMPT

class TaskGeneratorService:
    async def generate_tasks(self, request: GenerateTasksRequest) -> GenerateTasksResponse:
        user_prompt = GENERATE_TASKS_USER_PROMPT.format(
            project_context=request.project_context,
            description=request.description
        )
        return await llm_client.generate_structured(
            system_prompt=GENERATE_TASKS_SYSTEM_PROMPT,
            user_prompt=user_prompt,
            response_model=GenerateTasksResponse
        )

task_generator_service = TaskGeneratorService()
