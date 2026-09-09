const fs = require('fs');
const path = require('path');

const files = {
  "src/components/ui/Select.tsx": `import React from 'react';\nimport { cn } from '../../lib/utils';\n\nexport const Select = React.forwardRef<HTMLSelectElement, React.SelectHTMLAttributes<HTMLSelectElement> & { label?: string; error?: string }>(({ className, label, error, ...props }, ref) => (\n  <div className="w-full">\n    {label && <label className="block text-sm font-medium text-slate-700 mb-1">{label}</label>}\n    <select ref={ref} className={cn("flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500", error && "border-red-500 focus:ring-red-500", className)} {...props} />\n    {error && <p className="mt-1 text-sm text-red-500">{error}</p>}\n  </div>\n));\nSelect.displayName = 'Select';`,
  
  "src/components/ui/LoadingSpinner.tsx": `import React from 'react';\nexport const LoadingSpinner: React.FC = () => (\n  <div className="flex justify-center items-center p-4">\n    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>\n  </div>\n);`,
  
  "src/components/ui/EmptyState.tsx": `import React from 'react';\nexport const EmptyState: React.FC<{title: string, description: string}> = ({title, description}) => (\n  <div className="text-center p-8 bg-white border border-slate-200 rounded-lg shadow-sm">\n    <h3 className="mt-2 text-sm font-semibold text-slate-900">{title}</h3>\n    <p className="mt-1 text-sm text-slate-500">{description}</p>\n  </div>\n);`,

  "src/api/auth.ts": `import apiClient from './client';\nexport const authApi = {\n  login: async (creds: any) => (await apiClient.post('/auth/login', creds)).data,\n  register: async (creds: any) => (await apiClient.post('/auth/register', creds)).data,\n  me: async () => (await apiClient.get('/auth/me')).data,\n};`,

  "src/api/tasks.ts": `import apiClient from './client';\nimport { Task } from '../types/models';\nexport const tasksApi = {\n  getAll: async (projectId: string) => (await apiClient.get<Task[]>(\`/projects/\${projectId}/tasks\`)).data,\n  move: async (taskId: string, payload: any) => (await apiClient.put(\`/tasks/\${taskId}/move\`, payload)).data,\n};`,

  "src/hooks/useAuth.ts": `import { useMutation, useQuery } from '@tanstack/react-query';\nimport { authApi } from '../api/auth';\nimport { useAuthStore } from '../stores/authStore';\nexport const useAuth = () => {\n  const setAuth = useAuthStore(s => s.setAuth);\n  const login = useMutation({ mutationFn: authApi.login, onSuccess: (data) => setAuth(data.user, data.token) });\n  return { login: login.mutate, isLoading: login.isPending };\n};`,
  
  "src/hooks/useMercure.ts": `import { useEffect } from 'react';\nexport const useMercure = (topic: string, onMessage: (data: any) => void) => {\n  useEffect(() => {\n    const url = new URL('http://localhost:3000/.well-known/mercure');\n    url.searchParams.append('topic', topic);\n    const es = new EventSource(url.toString());\n    es.onmessage = e => onMessage(JSON.parse(e.data));\n    return () => es.close();\n  }, [topic]);\n};`,

  "src/hooks/useDebounce.ts": `import { useState, useEffect } from 'react';\nexport const useDebounce = <T>(value: T, delay: number): T => {\n  const [debounced, setDebounced] = useState(value);\n  useEffect(() => {\n    const handler = setTimeout(() => setDebounced(value), delay);\n    return () => clearTimeout(handler);\n  }, [value, delay]);\n  return debounced;\n};`,

  "Dockerfile": `FROM node:18-alpine\nWORKDIR /app\nCOPY package*.json ./\nRUN npm install\nCOPY . .\nEXPOSE 3000\nCMD ["npm", "run", "dev"]`,

  ".env.example": `VITE_API_URL=http://localhost:8000/api\nVITE_MERCURE_URL=http://localhost:3000/.well-known/mercure`,

  "src/lib/constants.ts": `export const APP_NAME = 'FlowBoard';\nexport const API_URL = import.meta.env.VITE_API_URL;`,

  "src/components/workspace/WorkspaceCard.tsx": `import React from 'react';\nimport { Workspace } from '../../types/models';\nexport const WorkspaceCard: React.FC<{workspace: Workspace}> = ({workspace}) => (\n  <div className="p-4 border rounded shadow-sm bg-white hover:border-primary-500 cursor-pointer">\n    <h3 className="font-bold">{workspace.name}</h3>\n  </div>\n);`
};

Object.entries(files).forEach(([filepath, content]) => {
  const fullPath = path.join('C:/Users/DARREN/Downloads/BUILDS/flowboard/frontend', filepath);
  fs.mkdirSync(path.dirname(fullPath), { recursive: true });
  fs.writeFileSync(fullPath, content);
  console.log('Created:', filepath);
});
