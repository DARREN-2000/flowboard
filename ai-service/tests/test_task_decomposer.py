import pytest
from app.models.responses import DecomposeTaskResponse

@pytest.mark.asyncio
async def test_decompose_task_mock_mode(client):
    response = client.post("/ai/decompose-task", json={
        "title": "Implement search",
        "description": "Add full text search to the product catalog",
        "context": "Using Postgres"
    })
    assert response.status_code == 200
    data = response.json()
    assert "subtasks" in data
    assert len(data["subtasks"]) > 0

@pytest.mark.asyncio
async def test_decompose_task_with_mocked_llm(client, mock_llm_generate):
    mock_llm_generate.return_value = DecomposeTaskResponse(
        subtasks=[{"title": "Test Subtask", "description": "Desc"}]
    )
    response = client.post("/ai/decompose-task", json={
        "title": "Task",
        "description": "Desc"
    })
    assert response.status_code == 200
    assert response.json()["subtasks"][0]["title"] == "Test Subtask"
