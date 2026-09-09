import apiClient from './client';
import { Project } from '../types/models';

export const projectsApi = {
  getAll: async (workspaceId: string) => {
    const { data } = await apiClient.get<Project[]>(`/workspaces/${workspaceId}/projects`);
    return data;
  },
  create: async (workspaceId: string, payload: { name: string; description?: string }) => {
    const { data } = await apiClient.post<Project>(`/workspaces/${workspaceId}/projects`, payload);
    return data;
  }
};
