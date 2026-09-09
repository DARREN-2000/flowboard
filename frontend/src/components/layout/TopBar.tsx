import React from 'react';
import { useAuthStore } from '../../stores/authStore';

const TopBar: React.FC = () => {
  const { user, logout } = useAuthStore();

  return (
    <header className="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">
      <div className="flex items-center">
        {/* Breadcrumbs or Title could go here */}
      </div>
      <div className="flex items-center space-x-4">
        <div className="text-sm text-slate-700">{user?.name}</div>
        <button onClick={logout} className="text-sm text-red-600 hover:text-red-800">
          Logout
        </button>
      </div>
    </header>
  );
};

export default TopBar;
