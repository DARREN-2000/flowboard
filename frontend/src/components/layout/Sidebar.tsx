import React from 'react';
import { Link } from 'react-router-dom';

const Sidebar: React.FC = () => {
  return (
    <aside className="w-64 bg-slate-900 text-white flex flex-col">
      <div className="p-4 text-xl font-bold border-b border-slate-800">
        <Link to="/">FlowBoard</Link>
      </div>
      <nav className="flex-1 overflow-y-auto p-4">
        <ul className="space-y-2">
          <li>
            <Link to="/" className="block px-4 py-2 rounded-md hover:bg-slate-800">
              Dashboard
            </Link>
          </li>
        </ul>
      </nav>
    </aside>
  );
};

export default Sidebar;
