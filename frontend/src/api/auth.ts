import apiClient from './client';

export const authApi = {
  login: async (credentials: any) => {
    const { data } = await apiClient.post('/auth/login', credentials);
    return data;
  },
  register: async (credentials: any) => {
    const { data } = await apiClient.post('/auth/register', credentials);
    return data;
  },
  me: async () => {
    const { data } = await apiClient.get('/auth/me');
    return data;
  }
};
