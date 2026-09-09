import pytest
from fastapi.testclient import TestClient
from unittest.mock import patch, AsyncMock
from app.main import app

@pytest.fixture
def client():
    return TestClient(app)

@pytest.fixture(autouse=True)
def mock_settings(monkeypatch):
    monkeypatch.setenv("USE_MOCK_LLM", "true")

@pytest.fixture
def mock_llm_generate():
    with patch("app.services.llm_client.LLMClient.generate_structured", new_callable=AsyncMock) as mock:
        yield mock
