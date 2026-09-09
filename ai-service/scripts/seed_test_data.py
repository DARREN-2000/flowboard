import asyncio
import httpx
import json

BASE_URL = "http://localhost:8000/ai"

async def test_generate():
    async with httpx.AsyncClient() as client:
        print("\n--- Testing Generate Tasks ---")
        response = await client.post(f"{BASE_URL}/generate-tasks", json={
            "project_context": "Creating a new social media app for pets",
            "description": "Build the user profile page with picture upload"
        })
        print(json.dumps(response.json(), indent=2))

async def test_decompose():
    async with httpx.AsyncClient() as client:
        print("\n--- Testing Decompose Task ---")
        response = await client.post(f"{BASE_URL}/decompose-task", json={
            "title": "Implement OAuth2 login",
            "description": "Add Google and GitHub login options",
            "context": "Frontend is React, Backend is FastAPI"
        })
        print(json.dumps(response.json(), indent=2))

async def test_summary():
    async with httpx.AsyncClient() as client:
        print("\n--- Testing Project Summary ---")
        response = await client.post(f"{BASE_URL}/project-summary", json={
            "tasks": [
                {"id": "1", "title": "Setup DB", "status": "done"},
                {"id": "2", "title": "API auth", "status": "in_progress"}
            ],
            "recent_activity": "Finished the database setup yesterday."
        })
        print(json.dumps(response.json(), indent=2))

async def test_search():
    async with httpx.AsyncClient() as client:
        print("\n--- Testing Search ---")
        response = await client.post(f"{BASE_URL}/search", json={
            "query": "auth related work",
            "tasks": [
                {"id": "1", "title": "Setup DB", "status": "done"},
                {"id": "2", "title": "API auth", "status": "in_progress"}
            ]
        })
        print(json.dumps(response.json(), indent=2))

async def main():
    await test_generate()
    await test_decompose()
    await test_summary()
    await test_search()

if __name__ == "__main__":
    asyncio.run(main())
