<?php


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
    private $pusher;

    /**
     * @param array $payload
     * @return string|void
     */
    protected function nextStage(array $payload)
    {
        if (!isset($payload[QueueStagePayload::KEY])) {
            return;
        }

        $stagePayload = QueueStagePayload::createFromArray($payload[QueueStagePayload::KEY]);

        $nextStageNumber = $stagePayload->getNextStageNumber();
        if ($nextStageNumber === null) {
            return;
        }

        $stagePayload->setCurrentStageNumber($nextStageNumber);

        return $this->pusher->push($payload, $stagePayload);
    }
}
