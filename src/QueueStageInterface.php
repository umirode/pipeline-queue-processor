<?php


namespace Umirode\PipelineQueueProcessor;


/**
 * Interface QueueStageInterface
 * @package Umirode\PipelineQueueProcessor
 */
interface QueueStageInterface
{
    /**
     * @param array $payload
     * @return mixed
     */
    public function __invoke(array $payload = []);
}
