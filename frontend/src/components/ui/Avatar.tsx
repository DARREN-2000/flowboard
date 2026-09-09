import React from 'react';
import { cn } from '../../lib/utils';
import { User } from '../../types/models';

interface AvatarProps {
  user?: User;
  size?: 'sm' | 'md' | 'lg';
  className?: string;
}

export const Avatar: React.FC<AvatarProps> = ({ user, size = 'md', className }) => {
  const getInitials = (name?: string) => {
    if (!name) return '?';
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
  };

  const sizeClasses = {
    sm: 'h-6 w-6 text-xs',
    md: 'h-8 w-8 text-sm',
    lg: 'h-10 w-10 text-base'
  };

  return (
    <div
      className={cn(
        "relative inline-flex items-center justify-center rounded-full bg-primary-100 text-primary-700 font-semibold uppercase border border-primary-200 overflow-hidden",
        sizeClasses[size],
        className
      )}
      title={user?.name}
    >
      {user?.avatarUrl ? (
        <img src={user.avatarUrl} alt={user.name} className="h-full w-full object-cover" />
      ) : (
        getInitials(user?.name)
      )}
    </div>
  );
};
