import React from 'react';
import { cn } from '../../lib/utils';
import { TaskStatus, TaskPriority } from '../../types/models';

interface BadgeProps extends React.HTMLAttributes<HTMLSpanElement> {
  variant?: 'status' | 'priority' | 'default';
  value: string;
}

export const Badge: React.FC<BadgeProps> = ({ className, variant = 'default', value, ...props }) => {
  const getColors = () => {
    if (variant === 'status') {
      switch (value) {
        case TaskStatus.DONE: return 'bg-green-100 text-green-800 border-green-200';
        case TaskStatus.IN_PROGRESS: return 'bg-blue-100 text-blue-800 border-blue-200';
        case TaskStatus.IN_REVIEW: return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case TaskStatus.TODO: return 'bg-slate-100 text-slate-800 border-slate-200';
        case TaskStatus.BACKLOG: return 'bg-gray-100 text-gray-600 border-gray-200';
        default: return 'bg-slate-100 text-slate-800 border-slate-200';
      }
    }
    if (variant === 'priority') {
      switch (value) {
        case TaskPriority.URGENT: return 'bg-red-100 text-red-800 border-red-200';
        case TaskPriority.HIGH: return 'bg-orange-100 text-orange-800 border-orange-200';
        case TaskPriority.MEDIUM: return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case TaskPriority.LOW: return 'bg-blue-100 text-blue-800 border-blue-200';
        default: return 'bg-slate-100 text-slate-800 border-slate-200';
      }
    }
    return 'bg-slate-100 text-slate-800 border-slate-200';
  };

  return (
    <span
      className={cn(
        "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border",
        getColors(),
        className
      )}
      {...props}
    >
      {value.replace('_', ' ')}
    </span>
  );
};
