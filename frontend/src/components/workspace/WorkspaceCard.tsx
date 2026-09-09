import { cn } from '../../lib/utils';
import type { Workspace } from '../../types/models';
import { Link } from 'react-router-dom';
import { FolderKanban, Users } from 'lucide-react';

interface WorkspaceCardProps {
  workspace: Workspace;
  className?: string;
}

export default function WorkspaceCard({ workspace, className }: WorkspaceCardProps) {
  return (
    <Link
      to={`/w/${workspace.slug}`}
      className={cn(
        'group block rounded-xl border border-slate-200 bg-white p-6',
        'shadow-sm transition-all hover:border-indigo-300 hover:shadow-md',
        className
      )}
    >
      <div className="flex items-start justify-between">
        <div className="flex-1 min-w-0">
          <h3 className="text-lg font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">
            {workspace.name}
          </h3>
          {workspace.description && (
            <p className="mt-1 text-sm text-slate-500 line-clamp-2">
              {workspace.description}
            </p>
          )}
        </div>
        <div className="ml-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
          <FolderKanban className="h-5 w-5" />
        </div>
      </div>

      <div className="mt-4 flex items-center gap-4 text-sm text-slate-500">
        <div className="flex items-center gap-1.5">
          <FolderKanban className="h-4 w-4" />
          <span>{workspace.projectCount ?? 0} projects</span>
        </div>
        <div className="flex items-center gap-1.5">
          <Users className="h-4 w-4" />
          <span>{workspace.memberCount ?? 0} members</span>
        </div>
      </div>

      <div className="mt-3 text-xs text-slate-400">
        Created {new Date(workspace.createdAt).toLocaleDateString()}
      </div>
    </Link>
  );
}
