from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import structlog
import logging

from app.routers import tasks, summary, search
from app.exceptions import AIServiceError, ai_service_error_handler, global_exception_handler

# Setup structlog
structlog.configure(
    processors=[
        structlog.stdlib.add_log_level,
        structlog.stdlib.PositionalArgumentsFormatter(),
        structlog.processors.TimeStamper(fmt="iso"),
        structlog.processors.StackInfoRenderer(),
        structlog.processors.format_exc_info,
        structlog.processors.JSONRenderer()
    ],
    context_class=dict,
    logger_factory=structlog.stdlib.LoggerFactory(),
    wrapper_class=structlog.stdlib.BoundLogger,
    cache_logger_on_first_use=True,
)

app = FastAPI(
    title="FlowBoard AI Service",
    description="AI service for FlowBoard tasks and project management",
    version="1.0.0",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.add_exception_handler(AIServiceError, ai_service_error_handler)
app.add_exception_handler(Exception, global_exception_handler)

app.include_router(tasks.router, prefix="/ai")
app.include_router(summary.router, prefix="/ai")
app.include_router(search.router, prefix="/ai")

@app.get("/health")
async def health_check():
    return {"status": "healthy", "version": "1.0.0"}
