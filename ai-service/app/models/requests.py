from pydantic import BaseModel, Field
from typing import List, Optional, Dict, Any

class GenerateTasksRequest(BaseModel):
    project_context: str = Field(..., description="Context about the project")
    description: str = Field(..., description="Free-text description of what needs to be done")

class DecomposeTaskRequest(BaseModel):
    title: str = Field(..., description="Title of the task")
    description: str = Field(..., description="Detailed description of the task")
    context: Optional[str] = Field(None, description="Optional context about the project")

class TaskData(BaseModel):
    id: str
    title: str
    status: str
    description: Optional[str] = None
    priority: Optional[str] = None

class ProjectSummaryRequest(BaseModel):
    tasks: List[TaskData] = Field(..., description="List of current project tasks")
    recent_activity: str = Field(..., description="Recent activity or comments")

class SearchRequest(BaseModel):
    query: str = Field(..., description="Natural language search query")
    tasks: List[TaskData] = Field(..., description="List of tasks to search over")
