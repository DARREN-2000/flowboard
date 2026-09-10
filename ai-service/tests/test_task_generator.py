import pytest
from app.models.responses import GenerateTasksResponse


def test_generate_tasks_mock_mode(client):
    response = client.post("/ai/generate-tasks", json={
        "project_context": "Building a new e-commerce site",
        "description": "Create the user authentication flow"
    })
    assert response.status_code == 200
    data = response.json()
    assert "tasks" in data
    assert len(data["tasks"]) > 0


def test_generate_tasks_returns_valid_structure(client):
    response = client.post("/ai/generate-tasks", json={
        "project_context": "Sensor reliability project",
        "description": "Investigate LiDAR failures in cold environments"
    })
    assert response.status_code == 200
    data = response.json()
    for task in data["tasks"]:
        assert "title" in task
        assert "description" in task
        assert "priority" in task


def test_generate_tasks_with_mocked_llm(client, mock_llm_generate):
    mock_llm_generate.return_value = GenerateTasksResponse(
        tasks=[{"title": "Test Task", "description": "Test Desc", "priority": "High"}]
    )
    response = client.post("/ai/generate-tasks", json={
        "project_context": "Test context",
        "description": "Test description"
    })
    assert response.status_code == 200
    assert response.json()["tasks"][0]["title"] == "Test Task"


def test_generate_tasks_empty_description(client):
    response = client.post("/ai/generate-tasks", json={
        "project_context": "Context",
        "description": ""
    })
    # Should return 422 for empty description or handle gracefully
    assert response.status_code in [200, 422]
