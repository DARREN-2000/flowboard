<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\CreateCommentRequest;
use App\DTO\Response\CommentResponse;
use App\Entity\Comment;
use App\Entity\Task;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tasks/{taskId}/comments')]
class CommentController extends AbstractController
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    #[Route('', methods: ['GET'])]
    public function index(string $taskId, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($taskId);
        $this->denyAccessUnlessGranted('VIEW', $task->getProject()->getWorkspace());

        $comments = $em->getRepository(Comment::class)->findByTask($task);
        $res = array_map(fn($c) => CommentResponse::fromEntity($c)->toArray(), $comments);

        return $this->json($res);
    }

    #[Route('', methods: ['POST'])]
    public function create(string $taskId, Request $request, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($taskId);
        $this->denyAccessUnlessGranted('VIEW', $task->getProject()->getWorkspace());

        $dto = CreateCommentRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }

        $comment = $this->commentService->createComment($task, $dto, $this->getUser());
        return $this->json(CommentResponse::fromEntity($comment)->toArray(), 201);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(string $taskId, Comment $comment): JsonResponse
    {
        if ($comment->getUser() !== $this->getUser()) {
            $this->denyAccessUnlessGranted('EDIT', $comment->getTask()->getProject()->getWorkspace());
        }

        $this->commentService->deleteComment($comment, $this->getUser());
        return $this->json(null, 204);
    }
}
