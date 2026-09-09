<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\GenerateTasksRequest;
use App\DTO\Response\AiSuggestionResponse;
use App\Entity\AiSuggestion;
use App\Entity\Project;
use App\Enum\AiSuggestionStatus;
use App\Enum\AiSuggestionType;
use App\Service\AiSuggestionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/projects/{projectId}/ai')]
class AiSuggestionController extends AbstractController
{
    private AiSuggestionService $aiService;

    public function __construct(AiSuggestionService $aiService)
    {
        $this->aiService = $aiService;
    }

    #[Route('/tasks/generate', methods: ['POST'])]
    public function generateTasks(string $projectId, Request $request, ValidatorInterface $validator, EntityManagerInterface $em, MessageBusInterface $bus): JsonResponse
    {
        $project = $em->getRepository(Project::class)->find($projectId);
        $this->denyAccessUnlessGranted('EDIT', $project->getWorkspace());

        $dto = GenerateTasksRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $suggestion = $this->aiService->createSuggestion($project, $this->getUser(), AiSuggestionType::TASK_GENERATION, ['prompt' => $dto->prompt]);
        
        // Dispatch message for async processing (to be implemented)
        // $bus->dispatch(new GenerateTasksMessage((string) $suggestion->getId()));

        return $this->json(AiSuggestionResponse::fromEntity($suggestion)->toArray(), 202);
    }

    #[Route('/suggestions', methods: ['GET'])]
    public function getPendingSuggestions(string $projectId, EntityManagerInterface $em): JsonResponse
    {
        $project = $em->getRepository(Project::class)->find($projectId);
        $this->denyAccessUnlessGranted('VIEW', $project->getWorkspace());

        $suggestions = $em->getRepository(AiSuggestion::class)->findPendingByProject($project);
        $res = array_map(fn($s) => AiSuggestionResponse::fromEntity($s)->toArray(), $suggestions);

        return $this->json($res);
    }

    #[Route('/suggestions/{id}/resolve', methods: ['POST'])]
    public function resolveSuggestion(string $projectId, AiSuggestion $suggestion, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('EDIT', $suggestion->getProject()->getWorkspace());

        $data = json_decode($request->getContent(), true);
        $status = AiSuggestionStatus::tryFrom($data['status'] ?? '') ?? AiSuggestionStatus::REJECTED;

        $this->aiService->resolveSuggestion($suggestion, $status);

        return $this->json(AiSuggestionResponse::fromEntity($suggestion)->toArray());
    }
}
