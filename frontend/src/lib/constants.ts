export const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
export const MERCURE_URL = import.meta.env.VITE_MERCURE_URL || 'http://localhost:3000/.well-known/mercure';

export const STATUS_COLORS = {
  BACKLOG: 'bg-gray-100 text-gray-800 border-gray-200',
  TODO: 'bg-slate-100 text-slate-800 border-slate-200',
  IN_PROGRESS: 'bg-blue-100 text-blue-800 border-blue-200',
  IN_REVIEW: 'bg-yellow-100 text-yellow-800 border-yellow-200',
  DONE: 'bg-green-100 text-green-800 border-green-200',
};

export const PRIORITY_LABELS = {
  LOW: 'Low',
  MEDIUM: 'Medium',
  HIGH: 'High',
  URGENT: 'Urgent',
};
