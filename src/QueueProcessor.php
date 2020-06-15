<?php


namespace Umirode\PipelineQueueProcessor;


use Umirode\Pipeline\ProcessorInterface;

/**
 * Class QueueProcessor
 * @package Umirode\PipelineQueueProcessor
 */
final class QueueProcessor implements ProcessorInterface
{
    /**
     * @var QueueStagePayloadPusher
     */
    private $pusher;

    /**
     * QueueProcessor constructor.
     * @param QueueStagePayloadPusher $pusher
     */
    public function __construct(QueueStagePayloadPusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @param array $payload
     * @param mixed ...$stages
     * @return mixed|string|void
     */
    public function process(array $payload, ...$stages)
    {
        if (count($stages) === 0) {
            return;
        }

        $stagePayload = QueueStagePayload::createFromArray(
            [
                'stages' => $stages
            ]
        );

        return $this->pusher->push($payload, $stagePayload);
    }
}
