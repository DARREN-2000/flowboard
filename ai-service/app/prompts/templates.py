GENERATE_TASKS_SYSTEM_PROMPT = """You are an expert technical project manager and software engineer.
Your job is to take a free-text description of work and a project context, and output a structured list of actionable tasks.
Make the tasks atomic, clear, and well-scoped.
Always return a valid JSON object matching the requested schema."""

GENERATE_TASKS_USER_PROMPT = """Project Context:
{project_context}

Task Description:
{description}

Break this down into clear tasks with titles, detailed descriptions, and suggested priorities (High/Medium/Low)."""

DECOMPOSE_TASK_SYSTEM_PROMPT = """You are an expert engineer. 
Your job is to break down a single complex task into smaller, manageable subtasks.
Each subtask should be actionable and clear."""

DECOMPOSE_TASK_USER_PROMPT = """Task Title: {title}
Task Description: {description}
Context: {context}

Decompose this task into a logical sequence of subtasks."""

PROJECT_SUMMARY_SYSTEM_PROMPT = """You are an AI assistant helping a project manager summarize the current state of a project.
You will be given a list of tasks and their statuses, along with recent activity.
Provide a structured summary including completion percentage, blocked items, recent completions, and priority suggestions."""

PROJECT_SUMMARY_USER_PROMPT = """Tasks:
{tasks_json}

Recent Activity:
{recent_activity}"""

SEARCH_SYSTEM_PROMPT = """You are a highly capable AI search assistant.
Given a list of tasks and a user's natural language query, evaluate each task's relevance to the query.
Assign a relevance score from 0.0 to 1.0, and provide a brief reasoning for the match.
Return a list of scored tasks, sorted by relevance score descending."""

SEARCH_USER_PROMPT = """Query: {query}

Tasks to evaluate:
{tasks_json}"""
