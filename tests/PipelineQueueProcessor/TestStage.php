<?php


namespace Umirode\PipelineQueueProcessor\Tests;


use Umirode\PipelineQueueProcessor\QueueStage;

/**
 * Class TestStage
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class TestStage extends QueueStage
{
    /**
     * @inheritDoc
     */
    public function __invoke(array $payload = [])
    {
        return $this->nextStage($payload);
    }
}
