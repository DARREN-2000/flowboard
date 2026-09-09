<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\CreateProjectRequest;
use App\DTO\Request\UpdateProjectRequest;
use App\DTO\Response\ProjectResponse;
use App\Entity\Project;
use App\Entity\Workspace;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/workspaces/{workspaceId}/projects')]
class ProjectController extends AbstractController
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    #[Route('', methods: ['GET'])]
    public function index(string $workspaceId, EntityManagerInterface $em): JsonResponse
    {
        $workspace = $em->getRepository(Workspace::class)->find($workspaceId);
        $this->denyAccessUnlessGranted('VIEW', $workspace);

        $projects = $em->getRepository(Project::class)->findByWorkspace($workspace);
        $res = array_map(fn($p) => ProjectResponse::fromEntity($p)->toArray(), $projects);

        return $this->json($res);
    }

    #[Route('', methods: ['POST'])]
    public function create(string $workspaceId, Request $request, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $workspace = $em->getRepository(Workspace::class)->find($workspaceId);
        $this->denyAccessUnlessGranted('EDIT', $workspace);

        $dto = CreateProjectRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $project = $this->projectService->createProject($workspace, $dto, $this->getUser());
        return $this->json(ProjectResponse::fromEntity($project)->toArray(), 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(string $workspaceId, Project $project): JsonResponse
    {
        $this->denyAccessUnlessGranted('VIEW', $project->getWorkspace());
        return $this->json(ProjectResponse::fromEntity($project)->toArray());
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(string $workspaceId, Project $project, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->denyAccessUnlessGranted('EDIT', $project->getWorkspace());

        $dto = UpdateProjectRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $project = $this->projectService->updateProject($project, $dto, $this->getUser());
        return $this->json(ProjectResponse::fromEntity($project)->toArray());
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(string $workspaceId, Project $project): JsonResponse
    {
        $this->denyAccessUnlessGranted('EDIT', $project->getWorkspace());
        $this->projectService->deleteProject($project, $this->getUser());
        return $this->json(null, 204);
    }
}
