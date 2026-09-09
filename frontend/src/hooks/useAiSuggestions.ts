import { useMutation } from '@tanstack/react-query';
import apiClient from '../api/client';

export const useAiSuggestions = (projectId: string) => {
  const generateMutation = useMutation({
    mutationFn: async (prompt: string) => {
      const { data } = await apiClient.post(`/ai/projects/${projectId}/generate-tasks`, { prompt });
      return data;
    }
  });

  return {
    generateTasks: generateMutation.mutate,
    isGenerating: generateMutation.isPending,
    suggestions: generateMutation.data || [],
  };
};
