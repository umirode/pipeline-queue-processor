<?php


namespace Umirode\PipelineQueueProcessor;


/**
 * Interface QueueInterface
 * @package Umirode\PipelineQueueProcessor
 */
interface QueueInterface
{
    /**
     * @param string $job
     * @param array $payload
     * @return bool
     */
    public function push(string $job, array $payload = []): bool;
}
