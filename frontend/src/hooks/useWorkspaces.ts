import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { workspacesApi } from '../api/workspaces';

export const useWorkspaces = () => {
  const queryClient = useQueryClient();

  const { data: workspaces = [], isLoading, error } = useQuery({
    queryKey: ['workspaces'],
    queryFn: workspacesApi.getAll,
  });

  const createWorkspace = useMutation({
    mutationFn: workspacesApi.create,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['workspaces'] });
    },
  });

  return {
    workspaces,
    isLoading,
    error,
    createWorkspace: createWorkspace.mutate,
    isCreating: createWorkspace.isPending
  };
};
