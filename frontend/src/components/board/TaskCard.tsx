import React from 'react';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { Task } from '../../types/models';
import { Badge } from '../ui/Badge';
import { Avatar } from '../ui/Avatar';

interface TaskCardProps {
  task: Task;
  onClick?: (task: Task) => void;
}

export const TaskCard: React.FC<TaskCardProps> = ({ task, onClick }) => {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: task.id, data: { type: 'Task', task } });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
  };

  if (isDragging) {
    return (
      <div
        ref={setNodeRef}
        style={style}
        className="bg-white rounded-lg p-4 shadow-sm border-2 border-primary-500 opacity-50 h-[120px]"
      />
    );
  }

  return (
    <div
      ref={setNodeRef}
      style={style}
      {...attributes}
      {...listeners}
      onClick={() => onClick?.(task)}
      className="bg-white rounded-lg p-4 shadow-sm border border-slate-200 hover:border-primary-300 hover:shadow-md transition-all cursor-grab active:cursor-grabbing"
    >
      <div className="flex justify-between items-start mb-2">
        <h4 className="text-sm font-medium text-slate-900 line-clamp-2">{task.title}</h4>
      </div>
      <div className="mt-4 flex items-center justify-between">
        <div className="flex gap-2">
          <Badge variant="priority" value={task.priority} />
        </div>
        <div className="flex -space-x-1">
          {task.assignee && <Avatar user={task.assignee} size="sm" />}
        </div>
      </div>
    </div>
  );
};
