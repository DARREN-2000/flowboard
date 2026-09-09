import { apiClient } from './client';

export interface Comment {
  id: string;
  taskId: string;
  userId: string;
  userName: string;
  body: string;
  createdAt: string;
  updatedAt: string;
}

export interface CreateCommentPayload {
  body: string;
}

export const commentsApi = {
  getTaskComments: (taskId: string): Promise<Comment[]> =>
    apiClient.get(`/tasks/${taskId}/comments`).then((r) => r.data),

  createComment: (taskId: string, data: CreateCommentPayload): Promise<Comment> =>
    apiClient.post(`/tasks/${taskId}/comments`, data).then((r) => r.data),

  deleteComment: (commentId: string): Promise<void> =>
    apiClient.delete(`/comments/${commentId}`),
};
