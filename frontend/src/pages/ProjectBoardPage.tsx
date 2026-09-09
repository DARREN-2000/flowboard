import React from 'react';
import { useParams } from 'react-router-dom';
import { KanbanBoard } from '../components/board/KanbanBoard';
import { useTasks } from '../hooks/useTasks';
import { Task } from '../types/models';

const ProjectBoardPage: React.FC = () => {
  const { projectSlug } = useParams<{ projectSlug: string }>();
  // In a real app we'd fetch the projectId by slug. Using a mock ID here for demo
  const projectId = 'mock-project-id';
  
  const { tasks, isLoading, moveTask } = useTasks(projectId);

  // Mock data if no backend
  const displayTasks = tasks.length > 0 ? tasks : [
    {
      id: '1', projectId: 'mock-project-id', title: 'Setup React Frontend', status: 'TODO', priority: 'HIGH',
      position: 0, reporterId: '1', reporter: { id: '1', email: 'a@a.com', name: 'Alice' },
      createdAt: new Date().toISOString(), updatedAt: new Date().toISOString()
    } as Task
  ];

  const handleTaskClick = (task: Task) => {
    console.log('Task clicked:', task);
    // Open modal or panel
  };

  return (
    <div className="h-full flex flex-col">
      <div className="mb-6 flex justify-between items-center">
        <h1 className="text-2xl font-bold">Project: {projectSlug}</h1>
      </div>
      <div className="flex-1 min-h-0">
        {isLoading ? (
          <div>Loading board...</div>
        ) : (
          <KanbanBoard 
            tasks={displayTasks}
            onTaskMove={(id, status, index) => moveTask(id, status, index)}
            onTaskClick={handleTaskClick}
          />
        )}
      </div>
    </div>
  );
};

export default ProjectBoardPage;
