import React, { createContext, useContext, useState, useCallback } from 'react';
import { cn } from '../../lib/utils';
import { CheckCircle, XCircle, AlertCircle, Info, X } from 'lucide-react';

type ToastType = 'success' | 'error' | 'warning' | 'info';

interface Toast {
  id: string;
  type: ToastType;
  title: string;
  message?: string;
}

interface ToastContextType {
  addToast: (toast: Omit<Toast, 'id'>) => void;
}

const ToastContext = createContext<ToastContextType | undefined>(undefined);

export const ToastProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [toasts, setToasts] = useState<Toast[]>([]);

  const addToast = useCallback((toast: Omit<Toast, 'id'>) => {
    const id = Math.random().toString(36).substring(2, 9);
    setToasts((prev) => [...prev, { ...toast, id }]);
    setTimeout(() => {
      setToasts((prev) => prev.filter((t) => t.id !== id));
    }, 5000);
  }, []);

  return (
    <ToastContext.Provider value={{ addToast }}>
      {children}
      <div className="fixed bottom-0 right-0 z-50 p-4 space-y-4">
        {toasts.map((toast) => (
          <div key={toast.id} className={cn("p-4 rounded-md shadow-lg flex items-start max-w-sm w-full bg-white border", {
            'border-green-200': toast.type === 'success',
            'border-red-200': toast.type === 'error',
            'border-yellow-200': toast.type === 'warning',
            'border-blue-200': toast.type === 'info',
          })}>
            <div className="flex-shrink-0">
              {toast.type === 'success' && <CheckCircle className="text-green-500" size={20} />}
              {toast.type === 'error' && <XCircle className="text-red-500" size={20} />}
              {toast.type === 'warning' && <AlertCircle className="text-yellow-500" size={20} />}
              {toast.type === 'info' && <Info className="text-blue-500" size={20} />}
            </div>
            <div className="ml-3 w-0 flex-1 pt-0.5">
              <p className="text-sm font-medium text-gray-900">{toast.title}</p>
              {toast.message && <p className="mt-1 text-sm text-gray-500">{toast.message}</p>}
            </div>
            <div className="ml-4 flex-shrink-0 flex">
              <button onClick={() => setToasts(t => t.filter(x => x.id !== toast.id))} className="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500">
                <X size={20} />
              </button>
            </div>
          </div>
        ))}
      </div>
    </ToastContext.Provider>
  );
};

export const useToast = () => {
  const context = useContext(ToastContext);
  if (!context) throw new Error('useToast must be used within ToastProvider');
  return context;
};
