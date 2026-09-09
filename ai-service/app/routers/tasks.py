from fastapi import APIRouter
from app.models.requests import GenerateTasksRequest, DecomposeTaskRequest
from app.models.responses import GenerateTasksResponse, DecomposeTaskResponse
from app.services.task_generator import task_generator_service
from app.services.task_decomposer import task_decomposer_service

router = APIRouter(tags=["tasks"])

@router.post("/generate-tasks", response_model=GenerateTasksResponse, status_code=200)
async def generate_tasks(request: GenerateTasksRequest):
    return await task_generator_service.generate_tasks(request)

@router.post("/decompose-task", response_model=DecomposeTaskResponse, status_code=200)
async def decompose_task(request: DecomposeTaskRequest):
    return await task_decomposer_service.decompose_task(request)
