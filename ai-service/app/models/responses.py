from pydantic import BaseModel, Field
from typing import List

class GeneratedTask(BaseModel):
    title: str = Field(..., description="A concise title for the task")
    description: str = Field(..., description="A detailed description of what needs to be done")
    priority: str = Field(..., description="Suggested priority: High, Medium, or Low")

class GenerateTasksResponse(BaseModel):
    tasks: List[GeneratedTask]

class Subtask(BaseModel):
    title: str = Field(..., description="Title of the subtask")
    description: str = Field(..., description="Description of the subtask")

class DecomposeTaskResponse(BaseModel):
    subtasks: List[Subtask]

class ProjectSummaryResponse(BaseModel):
    completion_percentage: int = Field(..., ge=0, le=100)
    blocked_items: List[str] = Field(..., description="List of blocked tasks or blockers")
    recent_completions: List[str] = Field(..., description="List of recently completed items")
    priority_suggestions: List[str] = Field(..., description="Suggested next steps")

class ScoredTask(BaseModel):
    task_id: str
    relevance_score: float = Field(..., ge=0.0, le=1.0)
    reasoning: str = Field(..., description="Why this task matches the query")

class SearchResponse(BaseModel):
    results: List[ScoredTask]
