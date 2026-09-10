import pytest
from fastapi.testclient import TestClient
from unittest.mock import patch, AsyncMock
from app.main import app
from app.config import settings


@pytest.fixture
def client():
    return TestClient(app)


@pytest.fixture(autouse=True)
def mock_settings():
    """Force mock LLM mode for all tests by directly modifying the settings instance."""
    original = settings.use_mock_llm
    settings.use_mock_llm = True
    yield
    settings.use_mock_llm = original


@pytest.fixture
def mock_llm_generate():
    with patch("app.services.llm_client.LLMClient.generate_structured", new_callable=AsyncMock) as mock:
        yield mock
