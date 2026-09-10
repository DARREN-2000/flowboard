import pytest
from app.models.responses import ProjectSummaryResponse


def test_project_summary_mock_mode(client):
    response = client.post("/ai/project-summary", json={
        "tasks": [
            {"id": "1", "title": "Task 1", "status": "completed"}
        ],
        "recent_activity": "Completed task 1"
    })
    assert response.status_code == 200
    data = response.json()
    assert "completion_percentage" in data
    assert "blocked_items" in data


def test_project_summary_has_all_fields(client):
    response = client.post("/ai/project-summary", json={
        "tasks": [
            {"id": "1", "title": "Task 1", "status": "completed"},
            {"id": "2", "title": "Task 2", "status": "in_progress"},
            {"id": "3", "title": "Task 3", "status": "backlog"}
        ],
        "recent_activity": "Completed task 1, started task 2"
    })
    assert response.status_code == 200
    data = response.json()
    assert isinstance(data["completion_percentage"], (int, float))
    assert isinstance(data["blocked_items"], list)
    assert isinstance(data["recent_completions"], list)
    assert isinstance(data["priority_suggestions"], list)


def test_project_summary_with_mocked_llm(client, mock_llm_generate):
    mock_llm_generate.return_value = ProjectSummaryResponse(
        completion_percentage=90,
        blocked_items=[],
        recent_completions=["Test"],
        priority_suggestions=["Test"]
    )
    response = client.post("/ai/project-summary", json={
        "tasks": [],
        "recent_activity": "None"
    })
    assert response.status_code == 200
    assert response.json()["completion_percentage"] == 90
