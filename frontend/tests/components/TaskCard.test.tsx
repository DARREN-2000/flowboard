import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import TaskCard from '../../src/components/board/TaskCard';

const mockTask = {
  id: '1',
  title: 'Investigate LiDAR failures',
  description: 'Look into intermittent LiDAR failures in cold environments',
  status: 'BACKLOG' as const,
  priority: 'HIGH' as const,
  assignee: {
    id: '1',
    fullName: 'John Doe',
    email: 'john@example.com',
  },
  dueDate: '2026-12-31',
  position: 0,
  projectId: 'proj-1',
  createdAt: '2026-01-01T00:00:00Z',
  updatedAt: '2026-01-01T00:00:00Z',
};

describe('TaskCard', () => {
  it('renders task title', () => {
    render(<TaskCard task={mockTask} onClick={() => {}} />);
    expect(screen.getByText('Investigate LiDAR failures')).toBeInTheDocument();
  });

  it('shows priority badge', () => {
    render(<TaskCard task={mockTask} onClick={() => {}} />);
    expect(screen.getByText('HIGH')).toBeInTheDocument();
  });

  it('shows assignee avatar', () => {
    render(<TaskCard task={mockTask} onClick={() => {}} />);
    expect(screen.getByText('JD')).toBeInTheDocument();
  });

  it('shows due date', () => {
    render(<TaskCard task={mockTask} onClick={() => {}} />);
    expect(screen.getByText(/Dec.*2026|12\/31\/2026/)).toBeInTheDocument();
  });

  it('is clickable', () => {
    let clicked = false;
    render(<TaskCard task={mockTask} onClick={() => { clicked = true; }} />);
    screen.getByText('Investigate LiDAR failures').click();
    expect(clicked).toBe(true);
  });
});
