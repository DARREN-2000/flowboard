import React from 'react';
import { Button } from './Button';

interface EmptyStateProps {
  title: string;
  description: string;
  actionText?: string;
  onAction?: () => void;
  icon?: React.ReactNode;
}

export const EmptyState: React.FC<EmptyStateProps> = ({ title, description, actionText, onAction, icon }) => (
  <div className="text-center p-12 bg-white border border-slate-200 rounded-lg shadow-sm flex flex-col items-center">
    {icon && <div className="text-slate-400 mb-4">{icon}</div>}
    <h3 className="text-lg font-semibold text-slate-900">{title}</h3>
    <p className="mt-2 text-sm text-slate-500 max-w-sm mb-6">{description}</p>
    {actionText && onAction && (
      <Button variant="primary" onClick={onAction}>{actionText}</Button>
    )}
  </div>
);
