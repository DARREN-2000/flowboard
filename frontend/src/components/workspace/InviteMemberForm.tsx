import { useState } from 'react';
import { Button } from '../ui/Button';
import { Input } from '../ui/Input';
import Select from '../ui/Select';
import { UserPlus } from 'lucide-react';

interface InviteMemberFormProps {
  onInvite: (email: string, role: string) => Promise<void>;
  isInviting?: boolean;
}

export default function InviteMemberForm({ onInvite, isInviting }: InviteMemberFormProps) {
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('MEMBER');
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');

    if (!email.trim()) {
      setError('Email is required');
      return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      setError('Please enter a valid email address');
      return;
    }

    try {
      await onInvite(email.trim(), role);
      setEmail('');
      setRole('MEMBER');
    } catch (err) {
      setError('Failed to invite member. They may already be a member.');
    }
  };

  return (
    <form onSubmit={handleSubmit} className="flex items-end gap-3">
      <div className="flex-1">
        <Input
          label="Email Address"
          type="email"
          placeholder="colleague@example.com"
          value={email}
          onChange={(e: any) => setEmail(e.target.value)}
          error={error}
        />
      </div>
      <div className="w-36">
        <Select
          label="Role"
          value={role}
          onChange={(value) => setRole(value)}
          options={[
            { value: 'ADMIN', label: 'Admin' },
            { value: 'MEMBER', label: 'Member' },
            { value: 'VIEWER', label: 'Viewer' },
          ]}
        />
      </div>
      <Button type="submit" disabled={isInviting} className="mb-0">
        <UserPlus className="mr-2 h-4 w-4" />
        {isInviting ? 'Inviting...' : 'Invite'}
      </Button>
    </form>
  );
}
