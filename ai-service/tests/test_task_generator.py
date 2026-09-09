import pytest
from app.models.responses import GenerateTasksResponse

@pytest.mark.asyncio
async def test_generate_tasks_mock_mode(client):
    response = client.post("/ai/generate-tasks", json={
        "project_context": "Building a new e-commerce site",
        "description": "Create the user authentication flow"
    })
    assert response.status_code == 200
    data = response.json()
    assert "tasks" in data
    assert len(data["tasks"]) > 0
    assert data["tasks"][0]["title"] == "Setup database schema"

@pytest.mark.asyncio
async def test_generate_tasks_with_mocked_llm(client, mock_llm_generate):
    mock_llm_generate.return_value = GenerateTasksResponse(
        tasks=[{"title": "Test Task", "description": "Test Desc", "priority": "High"}]
    )
    response = client.post("/ai/generate-tasks", json={
        "project_context": "Test context",
        "description": "Test description"
    })
    assert response.status_code == 200
    assert response.json()["tasks"][0]["title"] == "Test Task"
