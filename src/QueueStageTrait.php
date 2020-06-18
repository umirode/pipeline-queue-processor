<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor;

/**
 * Trait QueueStageTrait
 * @package Umirode\PipelineQueueProcessor
 */
trait QueueStageTrait
{
    /**
     * @var QueueStagePayloadPusher
     */
    protected $pusher;

    /**
     * @param array $payload
     * @return string|void
     */
    protected function nextStage(array $payload)
    {
        $stagePayload = $this->getStagePayload($payload);
        if ($stagePayload === null) {
            return;
        }

        $nextStageNumber = $stagePayload->getNextStageNumber();
        if ($nextStageNumber === null) {
            return;
        }

        $stagePayload->setCurrentStageNumber($nextStageNumber);

        return $this->pusher->push($payload, $stagePayload);
    }

    /**
     * @param array $payload
     * @return string|void|null
     */
    protected function repeatStage(array $payload)
    {
        $stagePayload = $this->getStagePayload($payload);
        if ($stagePayload === null) {
            return;
        }

        return $this->pusher->push($payload, $stagePayload);
    }

    /**
     * @param array $payload
     * @return QueueStagePayload|null
     */
    protected function getStagePayload(array $payload): ?QueueStagePayload
    {
        if (!isset($payload[QueueStagePayload::KEY])) {
            return null;
        }

        return QueueStagePayload::createFromArray($payload[QueueStagePayload::KEY]);
    }
}
