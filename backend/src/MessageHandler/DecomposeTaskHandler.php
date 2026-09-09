<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DecomposeTaskMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class DecomposeTaskHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(DecomposeTaskMessage $message): void
    {
        $this->logger->info('Processing DecomposeTaskMessage for suggestion: ' . $message->getSuggestionId());
    }
}
