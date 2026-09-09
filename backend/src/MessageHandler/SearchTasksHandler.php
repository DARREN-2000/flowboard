<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SearchTasksMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class SearchTasksHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(SearchTasksMessage $message): void
    {
        $this->logger->info('Processing SearchTasksMessage for suggestion: ' . $message->getSuggestionId());
    }
}
