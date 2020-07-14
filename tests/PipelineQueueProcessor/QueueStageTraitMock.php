<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use Umirode\PipelineQueueProcessor\QueueStage;
use Umirode\PipelineQueueProcessor\QueueStagePayloadPusher;
use Umirode\PipelineQueueProcessor\QueueStageTrait;

/**
 * Class TestStage
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class QueueStageTraitMock
{
    use QueueStageTrait {
        repeatStage as public repeatStageP;
        addNextStage as public addNextStageP;
        nextStage as public nextStageP;
        getStagePayload as public getStagePayloadP;
    }

    public function __construct(QueueStagePayloadPusher $pusher)
    {
        $this->pusher = $pusher;
    }
}
