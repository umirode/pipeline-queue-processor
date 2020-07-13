<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umirode\PipelineQueueProcessor\QueueStagePayload;
use Umirode\PipelineQueueProcessor\QueueStagePayloadPusher;

/**
 * Class QueueStageTest
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class QueueStageTest extends TestCase
{
    public function testEmpty(): void
    {
        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);

        $testStage = new TestStage($pusher);

        $result = $testStage();
        self::assertNull($result);

        $result = $testStage(
            [
                QueueStagePayload::KEY => [
                    QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                    QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
                    QueueStagePayload::KEY_CURRENT_STAGES => [
                        TestStage::class,
                    ],
                ]
            ]
        );
        self::assertNull($result);

        $result = $testStage(
            [
                QueueStagePayload::KEY => [
                    QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                    QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
                    QueueStagePayload::KEY_CURRENT_STAGES => [
                        TestStage::class,
                        TestStage::class,
                    ],
                ]
            ]
        );
        self::assertNull($result);
    }

    public function testSingleStage(): void
    {
        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);

        $testStage = new TestStage($pusher);

        $result = $testStage(
            [
                QueueStagePayload::KEY => [
                    QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                    QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
                    QueueStagePayload::KEY_CURRENT_STAGES => [
                        TestStage::class,
                    ],
                ]
            ]
        );
        self::assertNull($result);
    }


    public function testRepeatStage(): void
    {
        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);
        $pusher->method('push')->willReturn('repeat');

        $testStage = new TestStage($pusher);

        $result = $testStage(
            [
                'repeat' => true,
                QueueStagePayload::KEY => [
                    QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                    QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
                    QueueStagePayload::KEY_CURRENT_STAGES => [
                        TestStage::class,
                    ],
                ]
            ]
        );
        self::assertEquals('repeat', $result);

        $result = $testStage(
            [
                'repeat' => true,
            ]
        );
        self::assertNull($result);
    }

    public function testMultipleStage(): void
    {
        $testStagePayload = [
            QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
            QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
            QueueStagePayload::KEY_CURRENT_STAGES => [
                TestStage::class,
                TestStage::class,
            ]
        ];

        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);

        $pusher->method('push')->willReturn($testStagePayload[QueueStagePayload::KEY_PIPELINE_IDENTIFIER]);

        $testStage = new TestStage($pusher);

        $result = $testStage(
            [
                QueueStagePayload::KEY => $testStagePayload
            ]
        );
        self::assertEquals($testStagePayload[QueueStagePayload::KEY_PIPELINE_IDENTIFIER], $result);
    }
}
