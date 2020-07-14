<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umirode\PipelineQueueProcessor\QueueInterface;
use Umirode\PipelineQueueProcessor\QueueStagePayload;
use Umirode\PipelineQueueProcessor\QueueStagePayloadPusher;

/**
 * Class QueueStagePayloadPusherTest
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class QueueStagePayloadPusherTest extends TestCase
{
    public function testPushEmptyStagePayload(): void
    {
        /** @var QueueInterface|MockObject $queue */
        $queue = $this->createMock(QueueInterface::class);

        $pusher = new QueueStagePayloadPusher($queue);

        $result = $pusher->push([], new QueueStagePayload());
        self::assertNull($result);
    }

    public function testPushCorrectQueueResponse(): void
    {
        /** @var QueueInterface|MockObject $queue */
        $queue = $this->createMock(QueueInterface::class);

        $pusher = new QueueStagePayloadPusher($queue);

        $stagePayload = new QueueStagePayload(null, 0, [QueueStageTraitMock::class]);

        $queue->method('push')->willReturn(true);
        $result = $pusher->push([], $stagePayload);
        self::assertEquals($stagePayload->getPipelineIdentifier(), $result);
    }

    public function testPushIncorrectQueueResponse(): void
    {
        /** @var QueueInterface|MockObject $queue */
        $queue = $this->createMock(QueueInterface::class);

        $pusher = new QueueStagePayloadPusher($queue);

        $stagePayload = new QueueStagePayload(null, 0, [QueueStageTraitMock::class]);

        $queue->method('push')->willReturn(false);
        $result = $pusher->push([], $stagePayload);
        self::assertNull($result);
    }
}
