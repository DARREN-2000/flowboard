import { useState } from 'react';
import { Avatar } from '../ui/Avatar';
import { Badge } from '../ui/Badge';
import { Button } from '../ui/Button';
import { ConfirmDialog } from '../ui/ConfirmDialog';
import { Trash2, Shield, ShieldCheck, Eye, Crown } from 'lucide-react';

interface Member {
  id: string;
  userId: string;
  fullName: string;
  email: string;
  role: 'OWNER' | 'ADMIN' | 'MEMBER' | 'VIEWER';
  joinedAt: string;
}

interface MemberListProps {
  members: Member[];
  currentUserRole: string;
  onRemoveMember?: (userId: string) => void;
  isRemoving?: boolean;
}

const roleIcons = {
  OWNER: Crown,
  ADMIN: ShieldCheck,
  MEMBER: Shield,
  VIEWER: Eye,
};

const roleBadgeColors: Record<string, 'default' | 'primary' | 'success' | 'warning'> = {
  OWNER: 'warning',
  ADMIN: 'primary',
  MEMBER: 'default',
  VIEWER: 'default',
};

export default function MemberList({ members, currentUserRole, onRemoveMember, isRemoving }: MemberListProps) {
  const [confirmRemove, setConfirmRemove] = useState<string | null>(null);
  const canRemove = currentUserRole === 'OWNER' || currentUserRole === 'ADMIN';

  return (
    <>
      <div className="divide-y divide-slate-100">
        {members.map((member) => {
          const RoleIcon = roleIcons[member.role] ?? Shield;
          return (
            <div key={member.id} className="flex items-center justify-between py-3">
              <div className="flex items-center gap-3">
                <Avatar user={{ name: member.fullName, id: member.userId, email: member.email }} size="sm" />
                <div>
                  <p className="text-sm font-medium text-slate-900">{member.fullName}</p>
                  <p className="text-xs text-slate-500">{member.email}</p>
                </div>
              </div>
              <div className="flex items-center gap-2">
                <Badge variant="default" value={member.role}>
                  <RoleIcon className="mr-1 h-3 w-3" />
                  {member.role}
                </Badge>
                {canRemove && member.role !== 'OWNER' && onRemoveMember && (
                  <Button
                    variant="ghost"
                    size="sm"
                    onClick={() => setConfirmRemove(member.userId)}
                    disabled={isRemoving}
                    className="text-red-500 hover:text-red-700 hover:bg-red-50"
                  >
                    <Trash2 className="h-4 w-4" />
                  </Button>
                )}
              </div>
            </div>
          );
        })}
      </div>

      <ConfirmDialog
        isOpen={!!confirmRemove}
        onClose={() => setConfirmRemove(null)}
        onConfirm={() => {
          if (confirmRemove && onRemoveMember) {
            onRemoveMember(confirmRemove);
            setConfirmRemove(null);
          }
        }}
        title="Remove Member"
        message="Are you sure you want to remove this member from the workspace? They will lose access to all projects."
        confirmText="Remove"
        
      />
    </>
  );
}
