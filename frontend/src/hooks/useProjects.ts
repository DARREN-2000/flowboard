import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { projectsApi } from '../api/projects';

export function useProjects(workspaceId: string | undefined) {
  const queryClient = useQueryClient();

  const projectsQuery = useQuery({
    queryKey: ['projects', workspaceId],
    queryFn: () => projectsApi.getWorkspaceProjects(workspaceId!),
    enabled: !!workspaceId,
  });

  const createProjectMutation = useMutation({
    mutationFn: ({ name, description }: { name: string; description: string }) =>
      projectsApi.createProject(workspaceId!, { name, description }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['projects', workspaceId] });
    },
  });

  const updateProjectMutation = useMutation({
    mutationFn: ({ projectId, data }: { projectId: string; data: { name?: string; description?: string } }) =>
      projectsApi.updateProject(projectId, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['projects', workspaceId] });
    },
  });

  const deleteProjectMutation = useMutation({
    mutationFn: (projectId: string) => projectsApi.deleteProject(projectId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['projects', workspaceId] });
    },
  });

  return {
    projects: projectsQuery.data ?? [],
    isLoading: projectsQuery.isLoading,
    error: projectsQuery.error,
    createProject: createProjectMutation.mutateAsync,
    updateProject: updateProjectMutation.mutateAsync,
    deleteProject: deleteProjectMutation.mutateAsync,
    isCreating: createProjectMutation.isPending,
  };
}

export function useProject(projectId: string | undefined) {
  return useQuery({
    queryKey: ['project', projectId],
    queryFn: () => projectsApi.getProject(projectId!),
    enabled: !!projectId,
  });
}
