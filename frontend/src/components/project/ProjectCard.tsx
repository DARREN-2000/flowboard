import { Link } from 'react-router-dom';
import { cn } from '../../lib/utils';
import Badge from '../ui/Badge';
import { CheckCircle2, Circle, Clock, ListTodo } from 'lucide-react';

interface ProjectCardProps {
  project: {
    id: string;
    name: string;
    slug: string;
    description?: string;
    taskCounts?: {
      total: number;
      done: number;
      inProgress: number;
      backlog: number;
    };
  };
  workspaceSlug: string;
  className?: string;
}

export default function ProjectCard({ project, workspaceSlug, className }: ProjectCardProps) {
  const counts = project.taskCounts ?? { total: 0, done: 0, inProgress: 0, backlog: 0 };
  const progress = counts.total > 0 ? Math.round((counts.done / counts.total) * 100) : 0;

  return (
    <Link
      to={`/w/${workspaceSlug}/p/${project.slug}`}
      className={cn(
        'group block rounded-xl border border-slate-200 bg-white p-5',
        'shadow-sm transition-all hover:border-indigo-300 hover:shadow-md',
        className
      )}
    >
      <div className="flex items-start justify-between">
        <h3 className="text-base font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors">
          {project.name}
        </h3>
        <Badge variant={progress === 100 ? 'success' : progress > 0 ? 'primary' : 'default'}>
          {progress}%
        </Badge>
      </div>

      {project.description && (
        <p className="mt-1.5 text-sm text-slate-500 line-clamp-2">{project.description}</p>
      )}

      {/* Progress bar */}
      <div className="mt-4">
        <div className="h-1.5 w-full rounded-full bg-slate-100">
          <div
            className="h-1.5 rounded-full bg-indigo-500 transition-all duration-300"
            style={{ width: `${progress}%` }}
          />
        </div>
      </div>

      {/* Task stats */}
      <div className="mt-3 flex items-center gap-4 text-xs text-slate-500">
        <div className="flex items-center gap-1">
          <ListTodo className="h-3.5 w-3.5" />
          <span>{counts.total} tasks</span>
        </div>
        <div className="flex items-center gap-1">
          <Clock className="h-3.5 w-3.5 text-blue-500" />
          <span>{counts.inProgress} active</span>
        </div>
        <div className="flex items-center gap-1">
          <CheckCircle2 className="h-3.5 w-3.5 text-green-500" />
          <span>{counts.done} done</span>
        </div>
      </div>
    </Link>
  );
}
