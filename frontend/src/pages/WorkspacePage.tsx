import React from 'react';
import { useParams } from 'react-router-dom';

const WorkspacePage: React.FC = () => {
  const { workspaceSlug } = useParams<{ workspaceSlug: string }>();

  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-bold">Workspace: {workspaceSlug}</h1>
      <div className="bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
        <h2 className="text-lg font-semibold mb-4">Projects</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div className="p-4 border border-slate-200 rounded-md hover:border-primary-500 cursor-pointer">
            <h3 className="font-medium">Project Alpha</h3>
            <p className="text-sm text-slate-500 mt-1">Main development project</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default WorkspacePage;
