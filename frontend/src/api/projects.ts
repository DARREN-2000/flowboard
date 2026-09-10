import apiClient from './client';
import { Project } from '../types/models';

export const projectsApi = {
  getWorkspaceProjects: async (workspaceId: string) => {
    const { data } = await apiClient.get<Project[]>(`/workspaces/${workspaceId}/projects`);
    return data;
  },
  getProject: async (projectId: string) => {
    const { data } = await apiClient.get<Project>(`/projects/${projectId}`);
    return data;
  },
  createProject: async (workspaceId: string, payload: { name: string; description?: string }) => {
    const { data } = await apiClient.post<Project>(`/workspaces/${workspaceId}/projects`, payload);
    return data;
  },
  updateProject: async (projectId: string, payload: { name?: string; description?: string }) => {
    const { data } = await apiClient.patch<Project>(`/projects/${projectId}`, payload);
    return data;
  },
  deleteProject: async (projectId: string) => {
    await apiClient.delete(`/projects/${projectId}`);
  }
};
