import apiClient from './client';

export interface AiGenerateTasksPayload {
  description: string;
  projectContext?: string;
}

export interface AiDecomposePayload {
  taskTitle: string;
  taskDescription: string;
  context?: string;
}

export interface AiSearchPayload {
  query: string;
}

export interface AiGeneratedTask {
  title: string;
  description: string;
  priority: string;
}

export interface AiSuggestion {
  id: string;
  projectId: string;
  taskId: string | null;
  type: string;
  status: string;
  inputData: Record<string, unknown>;
  outputData: {
    tasks?: AiGeneratedTask[];
    subtasks?: AiGeneratedTask[];
    summary?: {
      completionPercentage: number;
      blockedItems: string[];
      recentlyCompleted: string[];
      suggestedPriority: string;
      narrative: string;
    };
    results?: Array<{
      taskId: string;
      title: string;
      relevanceScore: number;
      reason: string;
    }>;
  };
  createdAt: string;
  resolvedAt: string | null;
}

export const aiApi = {
  generateTasks: (projectId: string, data: AiGenerateTasksPayload): Promise<AiSuggestion> =>
    apiClient
      .post(`/projects/${projectId}/ai/generate-tasks`, data)
      .then((r: any) => r.data),

  decomposeTask: (taskId: string, data?: AiDecomposePayload): Promise<AiSuggestion> =>
    apiClient
      .post(`/tasks/${taskId}/ai/decompose`, data ?? {})
      .then((r: any) => r.data),

  getProjectSummary: (projectId: string): Promise<AiSuggestion> =>
    apiClient
      .post(`/projects/${projectId}/ai/summary`)
      .then((r: any) => r.data),

  searchTasks: (projectId: string, data: AiSearchPayload): Promise<AiSuggestion> =>
    apiClient
      .post(`/projects/${projectId}/ai/search`, data)
      .then((r: any) => r.data),

  getSuggestion: (suggestionId: string): Promise<AiSuggestion> =>
    apiClient.get(`/ai-suggestions/${suggestionId}`).then((r: any) => r.data),

  acceptSuggestion: (suggestionId: string, selectedIndexes?: number[]): Promise<void> =>
    apiClient.post(`/ai-suggestions/${suggestionId}/accept`, { selectedIndexes }),

  rejectSuggestion: (suggestionId: string): Promise<void> =>
    apiClient.post(`/ai-suggestions/${suggestionId}/reject`),
};
