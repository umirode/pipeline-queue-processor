<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor;

/**
 * Class QueueStagePayloadPusher
 * @package Umirode\PipelineQueueProcessor
 */
class QueueStagePayloadPusher
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * QueueStagePayloadPusher constructor.
     * @param QueueInterface $queue
     */
    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param array $payload
     * @param QueueStagePayload $stagePayload
     * @return string|null
     */
    public function push(array $payload, QueueStagePayload $stagePayload): ?string
    {
        $payload[QueueStagePayload::KEY] = $stagePayload->toArray();

        $stage = $stagePayload->getStage();
        if ($stage === null) {
            return null;
        }

        $status = $this->queue->push($stage, $payload);
        if ($status === false) {
            return null;
        }

        return $stagePayload->getPipelineIdentifier();
    }
}
