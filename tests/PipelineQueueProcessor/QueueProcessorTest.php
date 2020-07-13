<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umirode\PipelineQueueProcessor\QueueProcessor;
use Umirode\PipelineQueueProcessor\QueueStagePayloadPusher;

/**
 * Class QueueProcessorTest
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class QueueProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);
        $pusher->method('push')->willReturn('test_identifier');

        $processor = new QueueProcessor($pusher);

        $result = $processor->process([], ['TestStage']);
        self::assertEquals('test_identifier', $result);
    }

    public function testProcessEmpty(): void
    {
        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);

        $processor = new QueueProcessor($pusher);

        $result = $processor->process([]);
        self::assertNull($result);
    }
}
