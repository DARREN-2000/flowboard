<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\CreateWorkspaceRequest;
use App\DTO\Request\UpdateWorkspaceRequest;
use App\Entity\User;
use App\Entity\Workspace;
use App\Entity\WorkspaceMember;
use App\Enum\WorkspaceRole;
use Doctrine\ORM\EntityManagerInterface;

class WorkspaceService
{
    private EntityManagerInterface $em;
    private ActivityService $activityService;

    public function __construct(EntityManagerInterface $em, ActivityService $activityService)
    {
        $this->em = $em;
        $this->activityService = $activityService;
    }

    public function createWorkspace(CreateWorkspaceRequest $request, User $user): Workspace
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name), '-'));
        
        $workspace = new Workspace($request->name, $slug . '-' . substr(uniqid(), -5), $user);
        $workspace->setDescription($request->description);

        $member = new WorkspaceMember($workspace, $user, WorkspaceRole::OWNER);
        
        $this->em->persist($workspace);
        $this->em->persist($member);
        $this->em->flush();

        $this->activityService->logActivity($workspace, $user, 'workspace.created', ['name' => $workspace->getName()]);

        return $workspace;
    }

    public function updateWorkspace(Workspace $workspace, UpdateWorkspaceRequest $request, User $user): Workspace
    {
        $workspace->setName($request->name);
        if ($request->description !== null) {
            $workspace->setDescription($request->description);
        }

        $this->em->flush();

        $this->activityService->logActivity($workspace, $user, 'workspace.updated', ['name' => $workspace->getName()]);

        return $workspace;
    }

    public function inviteMember(Workspace $workspace, User $invitee, WorkspaceRole $role, User $inviter): void
    {
        $member = new WorkspaceMember($workspace, $invitee, $role);
        $this->em->persist($member);
        $this->em->flush();

        $this->activityService->logActivity($workspace, $inviter, 'workspace.member_invited', [
            'invited_user' => $invitee->getEmail(),
            'role' => $role->value
        ]);
    }

    public function deleteWorkspace(Workspace $workspace, User $user): void
    {
        $this->em->remove($workspace);
        $this->em->flush();
    }
}
