<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\GenerateSummaryMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class GenerateSummaryHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(GenerateSummaryMessage $message): void
    {
        $this->logger->info('Processing GenerateSummaryMessage for suggestion: ' . $message->getSuggestionId());
    }
}
