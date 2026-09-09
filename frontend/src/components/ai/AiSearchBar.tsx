import React, { useState } from 'react';
import { Input } from '../ui/Input';
import { useDebounce } from '../../hooks/useDebounce';
import { useQuery } from '@tanstack/react-query';
import apiClient from '../../api/client';
import { Task } from '../../types/models';

export const AiSearchBar: React.FC<{ projectId: string }> = ({ projectId }) => {
  const [query, setQuery] = useState('');
  const debouncedQuery = useDebounce(query, 500);

  const { data: results, isLoading } = useQuery({
    queryKey: ['ai-search', projectId, debouncedQuery],
    queryFn: async () => {
      if (!debouncedQuery) return [];
      const { data } = await apiClient.get<Task[]>(`/ai/projects/${projectId}/search?q=${debouncedQuery}`);
      return data;
    },
    enabled: debouncedQuery.length > 2
  });

  return (
    <div className="relative w-full max-w-md">
      <Input
        placeholder="Ask AI to find a task..."
        value={query}
        onChange={(e) => setQuery(e.target.value)}
        className="w-full pl-10"
      />
      <span className="absolute left-3 top-2.5 text-slate-400">🔍</span>
      
      {debouncedQuery && results && (
        <div className="absolute top-full mt-2 w-full bg-white rounded-lg shadow-lg border border-slate-200 z-50 max-h-64 overflow-y-auto">
          {isLoading ? (
            <div className="p-4 text-center text-sm text-slate-500">Searching...</div>
          ) : results.length > 0 ? (
            <ul className="divide-y divide-slate-100">
              {results.map(task => (
                <li key={task.id} className="p-3 hover:bg-slate-50 cursor-pointer">
                  <p className="text-sm font-medium text-slate-900">{task.title}</p>
                  <p className="text-xs text-slate-500 truncate">{task.description}</p>
                </li>
              ))}
            </ul>
          ) : (
            <div className="p-4 text-center text-sm text-slate-500">No matching tasks found.</div>
          )}
        </div>
      )}
    </div>
  );
};
