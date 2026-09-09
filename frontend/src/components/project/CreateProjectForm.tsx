import { useState } from 'react';
import Button from '../ui/Button';
import Input from '../ui/Input';
import TextArea from '../ui/TextArea';
import Modal from '../ui/Modal';
import { FolderPlus } from 'lucide-react';

interface CreateProjectFormProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (data: { name: string; description: string }) => Promise<void>;
  isCreating?: boolean;
}

export default function CreateProjectForm({ isOpen, onClose, onSubmit, isCreating }: CreateProjectFormProps) {
  const [name, setName] = useState('');
  const [description, setDescription] = useState('');
  const [errors, setErrors] = useState<{ name?: string; description?: string }>({});

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const newErrors: typeof errors = {};

    if (!name.trim()) {
      newErrors.name = 'Project name is required';
    } else if (name.trim().length < 3) {
      newErrors.name = 'Project name must be at least 3 characters';
    }

    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors);
      return;
    }

    setErrors({});

    try {
      await onSubmit({ name: name.trim(), description: description.trim() });
      setName('');
      setDescription('');
      onClose();
    } catch {
      setErrors({ name: 'Failed to create project. Please try again.' });
    }
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Create New Project">
      <form onSubmit={handleSubmit} className="space-y-4">
        <Input
          label="Project Name"
          placeholder="e.g., Q4 Sensor Reliability"
          value={name}
          onChange={(e) => setName(e.target.value)}
          error={errors.name}
          autoFocus
        />

        <TextArea
          label="Description"
          placeholder="Describe the project goals and scope..."
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          error={errors.description}
          rows={3}
        />

        <div className="flex justify-end gap-3 pt-2">
          <Button type="button" variant="secondary" onClick={onClose}>
            Cancel
          </Button>
          <Button type="submit" disabled={isCreating}>
            <FolderPlus className="mr-2 h-4 w-4" />
            {isCreating ? 'Creating...' : 'Create Project'}
          </Button>
        </div>
      </form>
    </Modal>
  );
}
