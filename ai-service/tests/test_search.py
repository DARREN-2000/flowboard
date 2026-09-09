import pytest
from app.models.responses import SearchResponse

@pytest.mark.asyncio
async def test_search_mock_mode(client):
    response = client.post("/ai/search", json={
        "query": "find authentication tasks",
        "tasks": [
            {"id": "mock-task-1", "title": "Auth", "status": "todo"}
        ]
    })
    assert response.status_code == 200
    data = response.json()
    assert "results" in data

@pytest.mark.asyncio
async def test_search_with_mocked_llm(client, mock_llm_generate):
    mock_llm_generate.return_value = SearchResponse(
        results=[{"task_id": "1", "relevance_score": 0.8, "reasoning": "match"}]
    )
    response = client.post("/ai/search", json={
        "query": "query",
        "tasks": [{"id": "1", "title": "T", "status": "todo"}]
    })
    assert response.status_code == 200
    assert response.json()["results"][0]["task_id"] == "1"
