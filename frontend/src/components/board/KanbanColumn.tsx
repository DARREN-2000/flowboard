import React from 'react';
import { useDroppable } from '@dnd-kit/core';
import { SortableContext, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { Task, TaskStatus } from '../../types/models';
import { TaskCard } from './TaskCard';

interface KanbanColumnProps {
  status: TaskStatus;
  tasks: Task[];
  onTaskClick?: (task: Task) => void;
}

export const KanbanColumn: React.FC<KanbanColumnProps> = ({ status, tasks, onTaskClick }) => {
  const { setNodeRef } = useDroppable({
    id: status,
    data: {
      type: 'Column',
      status,
    }
  });

  return (
    <div className="flex flex-col bg-slate-100 rounded-lg w-80 flex-shrink-0 max-h-full">
      <div className="p-3 border-b border-slate-200 flex items-center justify-between">
        <h3 className="font-semibold text-slate-700 flex items-center gap-2">
          {status.replace('_', ' ')}
          <span className="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-xs font-medium">
            {tasks.length}
          </span>
        </h3>
      </div>
      
      <div className="flex-1 overflow-y-auto p-2">
        <div ref={setNodeRef} className="flex flex-col gap-2 min-h-[150px]">
          <SortableContext items={tasks.map(t => t.id)} strategy={verticalListSortingStrategy}>
            {tasks.map(task => (
              <TaskCard key={task.id} task={task} onClick={onTaskClick} />
            ))}
          </SortableContext>
        </div>
      </div>
    </div>
  );
};
