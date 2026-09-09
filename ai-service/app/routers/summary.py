from fastapi import APIRouter
from app.models.requests import ProjectSummaryRequest
from app.models.responses import ProjectSummaryResponse
from app.services.summarizer import summarizer_service

router = APIRouter(tags=["summary"])

@router.post("/project-summary", response_model=ProjectSummaryResponse, status_code=200)
async def project_summary(request: ProjectSummaryRequest):
    return await summarizer_service.generate_summary(request)
