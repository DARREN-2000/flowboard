import apiClient from './client';
import { Workspace } from '../types/models';

export const workspacesApi = {
  getAll: async () => {
    const { data } = await apiClient.get<Workspace[]>('/workspaces');
    return data;
  },
  get: async (id: string) => {
    const { data } = await apiClient.get<Workspace>(`/workspaces/${id}`);
    return data;
  },
  create: async (payload: { name: string; slug: string }) => {
    const { data } = await apiClient.post<Workspace>('/workspaces', payload);
    return data;
  }
};
