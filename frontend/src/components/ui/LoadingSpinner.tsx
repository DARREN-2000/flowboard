import React from 'react';
import { cn } from '../../lib/utils';

export const LoadingSpinner: React.FC<{ className?: string }> = ({ className }) => (
  <div className={cn("flex justify-center items-center p-4", className)}>
    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
  </div>
);
