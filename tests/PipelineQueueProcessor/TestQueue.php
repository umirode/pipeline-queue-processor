<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use Umirode\PipelineQueueProcessor\QueueInterface;

/**
 * Class TestQueue
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class TestQueue implements QueueInterface
{
    /**
     * @inheritDoc
     */
    public function push(string $job, array $payload = []): bool
    {
        return (new $job())($payload);
    }
}
