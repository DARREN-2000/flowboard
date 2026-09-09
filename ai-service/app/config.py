from pydantic_settings import BaseSettings, SettingsConfigDict
from typing import Optional

class Settings(BaseSettings):
    environment: str = "development"
    log_level: str = "INFO"
    openai_api_key: Optional[str] = None
    anthropic_api_key: Optional[str] = None
    use_mock_llm: bool = False
    
    model_config = SettingsConfigDict(env_file=".env", env_file_encoding="utf-8")

settings = Settings()
