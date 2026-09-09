import React from 'react';
import { Button } from '../ui/Button';
import { Task } from '../../types/models';

interface AiSuggestionListProps {
  suggestions: Partial<Task>[];
  onAccept: (task: Partial<Task>) => void;
  onReject: (taskId: string) => void;
}

export const AiSuggestionList: React.FC<AiSuggestionListProps> = ({ suggestions, onAccept, onReject }) => {
  if (suggestions.length === 0) return null;

  return (
    <div className="space-y-3 mt-4">
      <h4 className="font-medium text-slate-800">AI Suggested Tasks</h4>
      {suggestions.map((suggestion, idx) => (
        <div key={suggestion.id || idx} className="bg-white p-3 rounded-lg border border-primary-200 flex justify-between items-center shadow-sm">
          <div>
            <p className="font-medium text-sm text-slate-900">{suggestion.title}</p>
            {suggestion.description && <p className="text-xs text-slate-500 mt-1 line-clamp-1">{suggestion.description}</p>}
          </div>
          <div className="flex gap-2">
            <Button size="sm" variant="ghost" onClick={() => onReject(suggestion.id || String(idx))}>Reject</Button>
            <Button size="sm" variant="primary" onClick={() => onAccept(suggestion)}>Accept</Button>
          </div>
        </div>
      ))}
    </div>
  );
};
