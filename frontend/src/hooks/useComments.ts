import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { commentsApi, type CreateCommentPayload } from '../api/comments';

export function useComments(taskId: string | undefined) {
  const queryClient = useQueryClient();

  const commentsQuery = useQuery({
    queryKey: ['comments', taskId],
    queryFn: () => commentsApi.getTaskComments(taskId!),
    enabled: !!taskId,
  });

  const createCommentMutation = useMutation({
    mutationFn: (data: CreateCommentPayload) =>
      commentsApi.createComment(taskId!, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['comments', taskId] });
    },
  });

  const deleteCommentMutation = useMutation({
    mutationFn: (commentId: string) => commentsApi.deleteComment(commentId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['comments', taskId] });
    },
  });

  return {
    comments: commentsQuery.data ?? [],
    isLoading: commentsQuery.isLoading,
    error: commentsQuery.error,
    addComment: createCommentMutation.mutateAsync,
    deleteComment: deleteCommentMutation.mutateAsync,
    isAdding: createCommentMutation.isPending,
  };
}
