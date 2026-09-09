import React from 'react';

const DashboardPage: React.FC = () => {
  return (
    <div>
      <h1 className="text-2xl font-bold mb-6">Dashboard</h1>
      <div className="p-8 text-center text-slate-500 bg-white rounded-lg border border-slate-200">
        Welcome to FlowBoard! Select or create a workspace to get started.
      </div>
    </div>
  );
};

export default DashboardPage;
