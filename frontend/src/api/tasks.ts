import apiClient from './client';
import type { Task } from '../types/models';

export interface CreateTaskPayload {
  title: string;
  description?: string;
  status: string;
  priority: string;
}

export interface UpdateTaskPayload {
  title?: string;
  description?: string;
}

export interface MoveTaskPayload {
  status: string;
  position: number;
}

export const tasksApi = {
  getProjectTasks: (projectId: string) => apiClient.get<Task[]>(`/projects/${projectId}/tasks`).then(r => r.data),
  getTask: (taskId: string) => apiClient.get<Task>(`/tasks/${taskId}`).then(r => r.data),
  createTask: (projectId: string, data: CreateTaskPayload) => apiClient.post<Task>(`/projects/${projectId}/tasks`, data).then(r => r.data),
  updateTask: (taskId: string, data: UpdateTaskPayload) => apiClient.put<Task>(`/tasks/${taskId}`, data).then(r => r.data),
  moveTask: (taskId: string, data: MoveTaskPayload) => apiClient.put<Task>(`/tasks/${taskId}/move`, data).then(r => r.data),
  deleteTask: (taskId: string) => apiClient.delete(`/tasks/${taskId}`),
};
