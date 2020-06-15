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
        $this->assertNull($result);

        $result = $testStage(
            [
                QueueStagePayload::KEY => [
                    'pipeline_identifier' => 'test',
                    'current_stage_number' => 0,
                    'stages' => [
                        TestStage::class,
                    ],
                ]
            ]
        );
        $this->assertNull($result);

        $result = $testStage(
            [
                QueueStagePayload::KEY => [
                    'pipeline_identifier' => 'test',
                    'current_stage_number' => 0,
                    'stages' => [
                        TestStage::class,
                        TestStage::class,
                    ],
                ]
            ]
        );
        $this->assertNull($result);
    }

    public function testSingleStage(): void
    {
        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);

        $testStage = new TestStage($pusher);

        $result = $testStage(
            [
                QueueStagePayload::KEY => [
                    'pipeline_identifier' => 'test',
                    'current_stage_number' => 0,
                    'stages' => [
                        TestStage::class,
                    ],
                ]
            ]
        );
        $this->assertNull($result);
    }

    public function testMultipleStage(): void
    {
        $testStagePayload = [
            'pipeline_identifier' => 'test',
            'current_stage_number' => 0,
            'stages' => [
                TestStage::class,
                TestStage::class,
            ]
        ];

        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);

        $pusher->method('push')->willReturn($testStagePayload['pipeline_identifier']);

        $testStage = new TestStage($pusher);

        $result = $testStage(
            [
                QueueStagePayload::KEY => $testStagePayload
            ]
        );
        $this->assertEquals($testStagePayload['pipeline_identifier'], $result);
    }
}
