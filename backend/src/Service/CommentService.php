<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\CreateCommentRequest;
use App\Entity\Comment;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    private EntityManagerInterface $em;
    private ActivityService $activityService;
    private MercurePublisher $mercurePublisher;

    public function __construct(EntityManagerInterface $em, ActivityService $activityService, MercurePublisher $mercurePublisher)
    {
        $this->em = $em;
        $this->activityService = $activityService;
        $this->mercurePublisher = $mercurePublisher;
    }

    public function createComment(Task $task, CreateCommentRequest $request, User $user): Comment
    {
        $comment = new Comment($task, $user, $request->body);
        $this->em->persist($comment);
        $this->em->flush();

        $this->activityService->logActivity(
            $task->getProject()->getWorkspace(),
            $user,
            'comment.created',
            ['task' => $task->getTitle()],
            $task->getProject(),
            $task
        );

        $this->mercurePublisher->publish(
            sprintf('/tasks/%s/comments', $task->getId()),
            \App\DTO\Response\CommentResponse::fromEntity($comment)->toArray()
        );

        return $comment;
    }

    public function deleteComment(Comment $comment, User $user): void
    {
        $task = $comment->getTask();
        $this->em->remove($comment);
        $this->em->flush();
    }
}
