<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use Umirode\PipelineQueueProcessor\QueueStage;
use Umirode\PipelineQueueProcessor\QueueStageInterface;
use Umirode\PipelineQueueProcessor\QueueStagePayloadPusher;
use Umirode\PipelineQueueProcessor\QueueStageTrait;

/**
 * Class TestStage
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class TestStage implements QueueStageInterface
{
    use QueueStageTrait;

    /**
     * TestStage constructor.
     * @param QueueStagePayloadPusher $pusher
     */
    public function __construct(QueueStagePayloadPusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $payload = [])
    {
        return $this->nextStage($payload);
    }
}
