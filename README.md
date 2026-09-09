<div align="center">

# 🚀 FlowBoard

### Real-Time AI Project Workspace

[![CI/CD](https://github.com/yourusername/flowboard/actions/workflows/ci.yml/badge.svg)](https://github.com/yourusername/flowboard/actions)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://php.net)
[![Symfony 7](https://img.shields.io/badge/Symfony-7.x-000000?logo=symfony)](https://symfony.com)
[![React 18](https://img.shields.io/badge/React-18-61DAFB?logo=react&logoColor=white)](https://react.dev)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?logo=typescript&logoColor=white)](https://typescriptlang.org)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A polished, production-grade collaborative project management application where teams manage workspaces, projects, and tasks in real time — with AI-assisted task generation, decomposition, and project summaries.

[**Live Demo**](https://flowboard.vercel.app) · [**API Docs**](#api-documentation) · [**Architecture**](#architecture)

</div>

---

## ✨ Features

### 📋 Project Management
- **Workspaces** — Organize teams with role-based access (Owner, Admin, Member, Viewer)
- **Projects** — Track initiatives with Kanban boards
- **Tasks** — Full lifecycle management with status, priority, assignments, and dependencies
- **Drag & Drop** — Move tasks between columns with instant visual feedback
- **Comments** — Collaborate on tasks with threaded comments
- **Activity Log** — Complete audit trail of all changes

### 🔄 Real-Time Collaboration
- **Live Updates** — See changes from teammates instantly (no refresh needed)
- **Powered by Mercure** — Server-Sent Events for efficient real-time communication
- **Optimistic UI** — Instant feedback with server reconciliation

### 🤖 AI-Powered Features
- **Task Generation** — Paste a description, get a structured task list
- **Task Decomposition** — Break complex tasks into actionable subtasks
- **Project Summary** — AI-generated status reports from real project data
- **Smart Search** — Find tasks using natural language queries
- **Human-in-the-Loop** — AI suggests, you decide. All AI proposals require explicit approval.

### 🔒 Security
- JWT authentication with refresh tokens
- Role-based authorization via Symfony Voters
- Input validation and sanitization
- CORS configuration
- AI suggestions cannot directly modify data

---

## 🏗 Architecture

```
                     INTERNET
                        │
                        ▼
            ┌─────────────────────┐
            │  React / TypeScript │
            │       (Vite)        │
            └──────────┬──────────┘
                       │ HTTPS
                       ▼
            ┌─────────────────────┐         ┌─────────────┐
            │   Symfony / PHP     │────────▶│  Mercure Hub │
            │      Backend        │         │    (SSE)     │
            └──────┬───────┬──────┘         └──────┬──────┘
                   │       │                       │
              PostgreSQL   Redis              Real-time
                   │                          updates to
                   ▼                          all clients
            ┌─────────────────────┐
            │    Python FastAPI   │
            │    AI Service       │
            │  ┌───────────────┐  │
            │  │ OpenAI / Claude│  │
            │  └───────────────┘  │
            └─────────────────────┘
```

### Tech Stack

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Frontend** | React 18, TypeScript, Vite, Tailwind CSS | Interactive SPA with Kanban board |
| **Backend** | PHP 8.3, Symfony 7, Doctrine ORM | REST API, business logic, auth |
| **Database** | PostgreSQL 16 | Persistent data storage |
| **Cache/Queue** | Redis 7 | Session store, Messenger transport |
| **Real-time** | Mercure | Server-Sent Events for live updates |
| **AI** | Python, FastAPI, OpenAI/Anthropic | Task generation, summaries, search |
| **DevOps** | Docker Compose, GitHub Actions | Local dev, CI/CD |

---

## 🚀 Quick Start

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/) & Docker Compose
- [Git](https://git-scm.com/)

### 1. Clone & Configure

```bash
git clone https://github.com/yourusername/flowboard.git
cd flowboard
cp .env.example .env
```

### 2. Start Everything

```bash
docker compose up --build
```

### 3. Access the Application

| Service | URL |
|---------|-----|
| 🌐 Frontend | http://localhost:5173 |
| 🔧 Backend API | http://localhost:8080/api |
| 🤖 AI Service | http://localhost:8000/docs |
| 📡 Mercure Hub | http://localhost:9090 |

### 4. Create Your First Account

Visit http://localhost:5173/register to create an account and start using FlowBoard.

> **💡 Tip:** AI features work in mock mode without API keys. Add your OpenAI or Anthropic API key to `.env` for real AI responses.

---

## 📁 Project Structure

```
flowboard/
│
├── frontend/                 # React + TypeScript SPA
│   ├── src/
│   │   ├── api/              # API client modules
│   │   ├── components/       # UI, layout, board, AI components
│   │   ├── hooks/            # Custom React hooks
│   │   ├── pages/            # Route pages
│   │   ├── stores/           # Zustand state management
│   │   └── types/            # TypeScript definitions
│   └── tests/
│
├── backend/                  # Symfony 7 PHP API
│   ├── src/
│   │   ├── Controller/       # REST API controllers
│   │   ├── Entity/           # Doctrine ORM entities
│   │   ├── Enum/             # PHP 8.1 backed enums
│   │   ├── Repository/       # Database queries
│   │   ├── Service/          # Business logic
│   │   ├── Security/Voter/   # Authorization
│   │   ├── DTO/              # Request/Response objects
│   │   ├── Message/          # Async messages
│   │   └── MessageHandler/   # Message processors
│   └── tests/
│
├── ai-service/               # Python FastAPI AI service
│   ├── app/
│   │   ├── routers/          # API endpoints
│   │   ├── services/         # LLM integration
│   │   ├── models/           # Pydantic schemas
│   │   └── prompts/          # Prompt templates
│   └── tests/
│
├── docker-compose.yml        # Full local environment
├── .github/workflows/ci.yml  # CI/CD pipeline
└── README.md
```

---

## 🧪 Testing

### Backend (PHPUnit)
```bash
docker compose exec backend vendor/bin/phpunit
```

### Frontend (Vitest)
```bash
docker compose exec frontend npm run test
```

### AI Service (pytest)
```bash
docker compose exec ai-service pytest tests/ -v
```

---

## 📡 API Documentation

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/auth/register` | Create account |
| `POST` | `/api/auth/login` | Get JWT token |
| `POST` | `/api/auth/refresh` | Refresh token |
| `GET` | `/api/auth/me` | Current user |

### Workspaces
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/workspaces` | List workspaces |
| `POST` | `/api/workspaces` | Create workspace |
| `GET` | `/api/workspaces/{id}` | Get workspace |
| `PUT` | `/api/workspaces/{id}` | Update workspace |
| `DELETE` | `/api/workspaces/{id}` | Delete workspace |

### Projects
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/workspaces/{wid}/projects` | List projects |
| `POST` | `/api/workspaces/{wid}/projects` | Create project |
| `GET` | `/api/projects/{id}` | Get project |
| `PUT` | `/api/projects/{id}` | Update project |

### Tasks
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/projects/{pid}/tasks` | List tasks |
| `POST` | `/api/projects/{pid}/tasks` | Create task |
| `GET` | `/api/tasks/{id}` | Get task |
| `PUT` | `/api/tasks/{id}` | Update task |
| `PATCH` | `/api/tasks/{id}/move` | Move task |

### AI Features
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/projects/{id}/ai/generate-tasks` | Generate tasks from description |
| `POST` | `/api/tasks/{id}/ai/decompose` | Decompose task into subtasks |
| `POST` | `/api/projects/{id}/ai/summary` | Generate project summary |
| `POST` | `/api/projects/{id}/ai/search` | Natural language task search |

---

## 🚢 Deployment

| Service | Platform | URL |
|---------|----------|-----|
| Frontend | Vercel | `flowboard.vercel.app` |
| Backend | Railway / Fly.io | `api.flowboard.app` |
| AI Service | Railway / Fly.io | `ai.flowboard.app` |
| Database | Managed PostgreSQL | — |
| Cache | Managed Redis | — |

---

## 📄 License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.

---

<div align="center">

**Built with** ❤️ **using PHP, Symfony, React, TypeScript, Python, and PostgreSQL**

</div>
