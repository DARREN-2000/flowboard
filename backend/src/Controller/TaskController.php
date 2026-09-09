<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\CreateTaskRequest;
use App\DTO\Request\MoveTaskRequest;
use App\DTO\Request\UpdateTaskRequest;
use App\DTO\Response\TaskResponse;
use App\Entity\Project;
use App\Entity\Task;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/projects/{projectId}/tasks')]
class TaskController extends AbstractController
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    #[Route('', methods: ['GET'])]
    public function index(string $projectId, EntityManagerInterface $em): JsonResponse
    {
        $project = $em->getRepository(Project::class)->find($projectId);
        $this->denyAccessUnlessGranted('VIEW', $project->getWorkspace());

        $tasks = $em->getRepository(Task::class)->findByProject($project);
        $res = array_map(fn($t) => TaskResponse::fromEntity($t)->toArray(), $tasks);

        return $this->json($res);
    }

    #[Route('', methods: ['POST'])]
    public function create(string $projectId, Request $request, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $project = $em->getRepository(Project::class)->find($projectId);
        $this->denyAccessUnlessGranted('EDIT', $project->getWorkspace());

        $dto = CreateTaskRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $task = $this->taskService->createTask($project, $dto, $this->getUser());
        return $this->json(TaskResponse::fromEntity($task)->toArray(), 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(string $projectId, Task $task, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->denyAccessUnlessGranted('EDIT', $task->getProject()->getWorkspace());

        $dto = UpdateTaskRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $task = $this->taskService->updateTask($task, $dto, $this->getUser());
        return $this->json(TaskResponse::fromEntity($task)->toArray());
    }

    #[Route('/{id}/move', methods: ['PUT'])]
    public function move(string $projectId, Task $task, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->denyAccessUnlessGranted('EDIT', $task->getProject()->getWorkspace());

        $dto = MoveTaskRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $task = $this->taskService->moveTask($task, $dto, $this->getUser());
        return $this->json(TaskResponse::fromEntity($task)->toArray());
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(string $projectId, Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('EDIT', $task->getProject()->getWorkspace());
        $this->taskService->deleteTask($task, $this->getUser());
        return $this->json(null, 204);
    }
}
