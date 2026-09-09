<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\GenerateTasksMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class GenerateTasksHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(GenerateTasksMessage $message): void
    {
        $this->logger->info('Processing GenerateTasksMessage for suggestion: ' . $message->getSuggestionId());
        // AI Integration logic would go here
    }
}
