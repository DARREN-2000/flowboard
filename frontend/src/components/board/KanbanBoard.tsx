import React, { useMemo } from 'react';
import { DndContext, DragOverlay, closestCorners, KeyboardSensor, PointerSensor, useSensor, useSensors, DragStartEvent, DragOverEvent, DragEndEvent } from '@dnd-kit/core';
import { SortableContext, arrayMove, sortableKeyboardCoordinates } from '@dnd-kit/sortable';
import { Task, TaskStatus } from '../../types/models';
import { KanbanColumn } from './KanbanColumn';
import { TaskCard } from './TaskCard';

interface KanbanBoardProps {
  tasks: Task[];
  onTaskMove: (taskId: string, newStatus: TaskStatus, newIndex: number) => void;
  onTaskClick: (task: Task) => void;
}

export const KanbanBoard: React.FC<KanbanBoardProps> = ({ tasks, onTaskMove, onTaskClick }) => {
  const [activeTask, setActiveTask] = React.useState<Task | null>(null);

  const columns = useMemo(() => {
    const cols = {
      [TaskStatus.BACKLOG]: [] as Task[],
      [TaskStatus.TODO]: [] as Task[],
      [TaskStatus.IN_PROGRESS]: [] as Task[],
      [TaskStatus.IN_REVIEW]: [] as Task[],
      [TaskStatus.DONE]: [] as Task[],
    };
    tasks.forEach(task => {
      if (cols[task.status]) {
        cols[task.status].push(task);
      }
    });
    // Sort within columns by position (mock logic, should be based on real position)
    Object.keys(cols).forEach(key => {
      cols[key as TaskStatus].sort((a, b) => a.position - b.position);
    });
    return cols;
  }, [tasks]);

  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(KeyboardSensor, { coordinateGetter: sortableKeyboardCoordinates })
  );

  const handleDragStart = (event: DragStartEvent) => {
    const { active } = event;
    const task = tasks.find(t => t.id === active.id);
    if (task) setActiveTask(task);
  };

  const handleDragEnd = (event: DragEndEvent) => {
    setActiveTask(null);
    const { active, over } = event;
    if (!over) return;

    const activeId = active.id;
    const overId = over.id;

    if (activeId === overId) return;

    const isActiveTask = active.data.current?.type === 'Task';
    const isOverTask = over.data.current?.type === 'Task';
    const isOverColumn = over.data.current?.type === 'Column';

    if (!isActiveTask) return;

    const activeTask = tasks.find(t => t.id === activeId);
    if (!activeTask) return;

    let newStatus = activeTask.status;
    let newIndex = 0;

    if (isOverColumn) {
      newStatus = over.data.current?.status as TaskStatus;
      newIndex = columns[newStatus].length;
    } else if (isOverTask) {
      const overTask = tasks.find(t => t.id === overId);
      if (overTask) {
        newStatus = overTask.status;
        const targetColumn = columns[newStatus];
        newIndex = targetColumn.findIndex(t => t.id === overId);
        
        if (activeTask.status === overTask.status) {
          const oldIndex = targetColumn.findIndex(t => t.id === activeId);
          if (oldIndex < newIndex) {
            newIndex--;
          }
        }
      }
    }

    onTaskMove(activeId as string, newStatus, newIndex);
  };

  return (
    <div className="flex h-full w-full overflow-x-auto gap-6 pb-4">
      <DndContext
        sensors={sensors}
        collisionDetection={closestCorners}
        onDragStart={handleDragStart}
        onDragEnd={handleDragEnd}
      >
        {(Object.keys(columns) as TaskStatus[]).map(status => (
          <KanbanColumn
            key={status}
            status={status}
            tasks={columns[status]}
            onTaskClick={onTaskClick}
          />
        ))}

        <DragOverlay>
          {activeTask ? <TaskCard task={activeTask} /> : null}
        </DragOverlay>
      </DndContext>
    </div>
  );
};
