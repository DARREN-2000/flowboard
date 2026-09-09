<?php
declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Workspace;
use App\Entity\WorkspaceMember;
use App\Enum\WorkspaceRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class WorkspaceVoter extends Voter
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ['VIEW', 'EDIT', 'DELETE']) && $subject instanceof Workspace;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user) return false;

        /** @var Workspace $workspace */
        $workspace = $subject;

        $member = $this->em->getRepository(WorkspaceMember::class)->findByWorkspaceAndUser($workspace, $user);
        if (!$member) return false;

        $role = $member->getRole();

        return match ($attribute) {
            'VIEW' => true,
            'EDIT' => in_array($role, [WorkspaceRole::OWNER, WorkspaceRole::ADMIN, WorkspaceRole::MEMBER]),
            'DELETE' => $role === WorkspaceRole::OWNER,
            default => false,
        };
    }
}
