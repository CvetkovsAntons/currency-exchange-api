<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\ExchangeRateSyncMessage;
use App\Service\Domain\ExchangeRateService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
readonly class ExchangeRateSyncMessageHandler
{
    public function __construct(
        private ExchangeRateService $service,
        #[Autowire(service: 'monolog.logger.messenger')]
        private LoggerInterface     $logger,
    ) {}

    public function __invoke(ExchangeRateSyncMessage $message): void
    {
        try {
            $this->service->syncAll();
            $this->logger->info('Exchange rate synchronization task has been completed');
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
        }
    }

}
