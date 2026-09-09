<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\CreateWorkspaceRequest;
use App\DTO\Request\UpdateWorkspaceRequest;
use App\DTO\Response\WorkspaceDetailResponse;
use App\DTO\Response\WorkspaceResponse;
use App\Entity\Workspace;
use App\Service\WorkspaceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/workspaces')]
class WorkspaceController extends AbstractController
{
    private WorkspaceService $workspaceService;

    public function __construct(WorkspaceService $workspaceService)
    {
        $this->workspaceService = $workspaceService;
    }

    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        $members = $em->getRepository(\App\Entity\WorkspaceMember::class)->findByUser($user);
        
        $res = array_map(fn($m) => WorkspaceResponse::fromEntity($m->getWorkspace())->toArray(), $members);
        return $this->json($res);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $dto = CreateWorkspaceRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $workspace = $this->workspaceService->createWorkspace($dto, $this->getUser());
        return $this->json(WorkspaceDetailResponse::fromEntity($workspace)->toArray(), 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Workspace $workspace): JsonResponse
    {
        $this->denyAccessUnlessGranted('VIEW', $workspace);
        return $this->json(WorkspaceDetailResponse::fromEntity($workspace)->toArray());
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Workspace $workspace, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->denyAccessUnlessGranted('EDIT', $workspace);
        
        $dto = UpdateWorkspaceRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $workspace = $this->workspaceService->updateWorkspace($workspace, $dto, $this->getUser());
        return $this->json(WorkspaceDetailResponse::fromEntity($workspace)->toArray());
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Workspace $workspace): JsonResponse
    {
        $this->denyAccessUnlessGranted('DELETE', $workspace);
        $this->workspaceService->deleteWorkspace($workspace, $this->getUser());
        return $this->json(null, 204);
    }
}
