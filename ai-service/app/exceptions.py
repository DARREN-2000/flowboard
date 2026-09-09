from fastapi import Request
from fastapi.responses import JSONResponse
from structlog import get_logger

logger = get_logger(__name__)

class AIServiceError(Exception):
    def __init__(self, message: str, status_code: int = 500, details: dict = None):
        self.message = message
        self.status_code = status_code
        self.details = details or {}
        super().__init__(message)

class LLMProviderError(AIServiceError):
    pass

class PromptValidationError(AIServiceError):
    pass

async def ai_service_error_handler(request: Request, exc: AIServiceError):
    logger.error("ai_service_error", message=exc.message, status_code=exc.status_code, details=exc.details, url=str(request.url))
    return JSONResponse(
        status_code=exc.status_code,
        content={"detail": exc.message, "details": exc.details},
    )

async def global_exception_handler(request: Request, exc: Exception):
    logger.exception("unhandled_exception", error=str(exc), url=str(request.url))
    return JSONResponse(
        status_code=500,
        content={"detail": "Internal server error"},
    )
