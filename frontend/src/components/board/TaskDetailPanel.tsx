import React from 'react';
import { Task } from '../../types/models';
import { Badge } from '../ui/Badge';
import { Button } from '../ui/Button';

interface TaskDetailPanelProps {
  task: Task;
  onClose: () => void;
  isOpen: boolean;
}

export const TaskDetailPanel: React.FC<TaskDetailPanelProps> = ({ task, onClose, isOpen }) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-y-0 right-0 z-40 w-full max-w-md bg-white shadow-xl border-l border-gray-200 transform transition-transform">
      <div className="h-full flex flex-col">
        <div className="px-4 py-6 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
          <h2 className="text-lg font-medium text-gray-900">{task.title}</h2>
          <button onClick={onClose} className="text-gray-400 hover:text-gray-500 text-xl font-bold">&times;</button>
        </div>
        <div className="flex-1 overflow-y-auto p-4 space-y-6">
          <div>
            <h3 className="text-sm font-medium text-gray-500">Status</h3>
            <div className="mt-2"><Badge variant="status" value={task.status} /></div>
          </div>
          <div>
            <h3 className="text-sm font-medium text-gray-500">Priority</h3>
            <div className="mt-2"><Badge variant="priority" value={task.priority} /></div>
          </div>
          <div>
            <h3 className="text-sm font-medium text-gray-500">Description</h3>
            <p className="mt-2 text-sm text-gray-900">{task.description || 'No description provided.'}</p>
          </div>
        </div>
        <div className="p-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
          <Button variant="secondary" onClick={onClose}>Close</Button>
          <Button variant="primary">Edit Task</Button>
        </div>
      </div>
    </div>
  );
};
