<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\InviteMemberRequest;
use App\Entity\User;
use App\Entity\Workspace;
use App\Entity\WorkspaceMember;
use App\Enum\WorkspaceRole;
use App\Service\WorkspaceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/workspaces/{workspaceId}/members')]
class WorkspaceMemberController extends AbstractController
{
    private WorkspaceService $workspaceService;

    public function __construct(WorkspaceService $workspaceService)
    {
        $this->workspaceService = $workspaceService;
    }

    #[Route('', methods: ['GET'])]
    public function index(string $workspaceId, EntityManagerInterface $em): JsonResponse
    {
        $workspace = $em->getRepository(Workspace::class)->find($workspaceId);
        $this->denyAccessUnlessGranted('VIEW', $workspace);

        $members = $em->getRepository(WorkspaceMember::class)->findBy(['workspace' => $workspace]);
        $res = array_map(function($m) {
            return [
                'id' => (string) $m->getId(),
                'user' => [
                    'id' => (string) $m->getUser()->getId(),
                    'email' => $m->getUser()->getEmail(),
                    'fullName' => $m->getUser()->getFullName(),
                ],
                'role' => $m->getRole()->value,
                'joinedAt' => $m->getJoinedAt()->format(\DateTimeInterface::ATOM)
            ];
        }, $members);

        return $this->json($res);
    }

    #[Route('', methods: ['POST'])]
    public function invite(string $workspaceId, Request $request, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $workspace = $em->getRepository(Workspace::class)->find($workspaceId);
        $this->denyAccessUnlessGranted('EDIT', $workspace);

        $dto = InviteMemberRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $invitee = $em->getRepository(User::class)->findByEmail($dto->email);
        if (!$invitee) {
            return $this->json(['message' => 'User not found'], 404);
        }

        $role = WorkspaceRole::tryFrom($dto->role) ?? WorkspaceRole::MEMBER;
        $this->workspaceService->inviteMember($workspace, $invitee, $role, $this->getUser());

        return $this->json(['message' => 'User invited'], 201);
    }

    #[Route('/{userId}', methods: ['DELETE'])]
    public function remove(string $workspaceId, string $userId, EntityManagerInterface $em): JsonResponse
    {
        $workspace = $em->getRepository(Workspace::class)->find($workspaceId);
        $this->denyAccessUnlessGranted('EDIT', $workspace);

        $user = $em->getRepository(User::class)->find($userId);
        $member = $em->getRepository(WorkspaceMember::class)->findByWorkspaceAndUser($workspace, $user);

        if ($member) {
            $em->remove($member);
            $em->flush();
        }

        return $this->json(null, 204);
    }
}
