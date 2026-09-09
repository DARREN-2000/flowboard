import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import apiClient from '../api/client';
import { Task, TaskStatus } from '../types/models';

export const useTasks = (projectId: string) => {
  const queryClient = useQueryClient();

  const { data: tasks, isLoading, error } = useQuery({
    queryKey: ['tasks', projectId],
    queryFn: async () => {
      const { data } = await apiClient.get<Task[]>(`/projects/${projectId}/tasks`);
      return data;
    },
  });

  const moveTaskMutation = useMutation({
    mutationFn: async ({ taskId, status, position }: { taskId: string; status: TaskStatus; position: number }) => {
      const { data } = await apiClient.put<Task>(`/tasks/${taskId}/move`, { status, position });
      return data;
    },
    onMutate: async (variables) => {
      await queryClient.cancelQueries({ queryKey: ['tasks', projectId] });
      const previousTasks = queryClient.getQueryData<Task[]>(['tasks', projectId]);
      
      if (previousTasks) {
        queryClient.setQueryData(['tasks', projectId], previousTasks.map(task => {
          if (task.id === variables.taskId) {
            return { ...task, status: variables.status, position: variables.position };
          }
          return task;
        }));
      }
      return { previousTasks };
    },
    onError: (err, variables, context) => {
      if (context?.previousTasks) {
        queryClient.setQueryData(['tasks', projectId], context.previousTasks);
      }
    },
    onSettled: () => {
      queryClient.invalidateQueries({ queryKey: ['tasks', projectId] });
    },
  });

  return {
    tasks: tasks || [],
    isLoading,
    error,
    moveTask: (taskId: string, status: TaskStatus, position: number) => {
      moveTaskMutation.mutate({ taskId, status, position });
    }
  };
};
