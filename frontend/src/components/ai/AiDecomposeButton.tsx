import React from 'react';
import { Button } from '../ui/Button';
import { useMutation } from '@tanstack/react-query';
import apiClient from '../../api/client';

export const AiDecomposeButton: React.FC<{ taskId: string; onSuccess: () => void }> = ({ taskId, onSuccess }) => {
  const decomposeMutation = useMutation({
    mutationFn: async () => {
      const { data } = await apiClient.post(`/ai/tasks/${taskId}/decompose`);
      return data;
    },
    onSuccess: () => onSuccess()
  });

  return (
    <Button
      variant="secondary"
      size="sm"
      onClick={() => decomposeMutation.mutate()}
      isLoading={decomposeMutation.isPending}
      className="w-full flex justify-center gap-2"
    >
      <span role="img" aria-label="split">🧩</span> Decompose into Subtasks
    </Button>
  );
};
