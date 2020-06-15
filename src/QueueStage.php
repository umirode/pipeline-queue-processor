<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor;

/**
 * Class QueueStage
 * @package Umirode\PipelineQueueProcessor
 */
abstract class QueueStage
{
    /**
     * @var QueueStagePayloadPusher
     */
    private $pusher;

    /**
     * QueueStage constructor.
     * @param QueueStagePayloadPusher $pusher
     */
    public function __construct(QueueStagePayloadPusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @param array $payload
     * @return mixed
     */
    abstract public function __invoke(array $payload = []);

    /**
     * @param array $payload
     * @return string|void
     */
    protected function nextStage(array $payload)
    {
        $stagePayload = QueueStagePayload::createFromArray($payload[QueueStagePayload::KEY]);

        $nextStageNumber = $stagePayload->getNextStageNumber();
        if ($nextStageNumber === null) {
            return;
        }

        $stagePayload->setCurrentStageNumber($nextStageNumber);

        return $this->pusher->push($payload, $stagePayload);
    }
}
