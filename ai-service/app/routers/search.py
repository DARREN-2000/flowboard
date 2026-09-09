from fastapi import APIRouter
from app.models.requests import SearchRequest
from app.models.responses import SearchResponse
from app.services.search_service import search_service

router = APIRouter(tags=["search"])

@router.post("/search", response_model=SearchResponse, status_code=200)
async def search(request: SearchRequest):
    return await search_service.search_tasks(request)
