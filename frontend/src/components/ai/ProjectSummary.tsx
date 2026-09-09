import React from 'react';
import { useQuery } from '@tanstack/react-query';
import apiClient from '../../api/client';
import { LoadingSpinner } from '../ui/LoadingSpinner';

export const ProjectSummary: React.FC<{ projectId: string }> = ({ projectId }) => {
  const { data, isLoading } = useQuery({
    queryKey: ['project', projectId, 'ai-summary'],
    queryFn: async () => {
      const { data } = await apiClient.get<{ summary: string, healthScore: number }>(`/ai/projects/${projectId}/summary`);
      return data;
    }
  });

  if (isLoading) return <LoadingSpinner />;
  if (!data) return null;

  return (
    <div className="bg-gradient-to-br from-primary-50 to-white p-4 rounded-xl border border-primary-100 shadow-sm">
      <div className="flex items-center justify-between mb-2">
        <h3 className="font-semibold text-primary-900 flex items-center gap-2">
          <span>🤖</span> AI Project Insights
        </h3>
        <span className="text-sm font-medium px-2 py-1 bg-green-100 text-green-800 rounded-full">
          Health: {data.healthScore}/100
        </span>
      </div>
      <p className="text-sm text-slate-700 leading-relaxed">{data.summary}</p>
    </div>
  );
};
