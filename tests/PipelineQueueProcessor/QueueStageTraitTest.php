<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umirode\PipelineQueueProcessor\QueueStagePayload;
use Umirode\PipelineQueueProcessor\QueueStagePayloadPusher;

final class QueueStageTraitTest extends TestCase
{
    public function testRepeatStage(): void
    {
        $pusher = $this->getPusherMock('repeat');

        $trait = new QueueStageTraitMock($pusher);

        $result = $trait->repeatStageP(
            [
                QueueStagePayload::KEY => [
                    QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                    QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
                    QueueStagePayload::KEY_CURRENT_STAGES => [],
                ]
            ]
        );

        self::assertEquals('repeat', $result);
    }

    public function testGetStagePayload(): void
    {
        $pusher = $this->getPusherMock('');

        $trait = new QueueStageTraitMock($pusher);

        $payload = [
            QueueStagePayload::KEY => [
                QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
                QueueStagePayload::KEY_CURRENT_STAGES => [],
            ]
        ];

        $result = $trait->getStagePayloadP($payload);

        self::assertEquals($payload[QueueStagePayload::KEY], $result->toArray());
    }

    public function testAddNextStage(): void
    {
        $pusher = $this->getPusherMock('');

        $trait = new QueueStageTraitMock($pusher);

        $payload = [
            QueueStagePayload::KEY => [
                QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 0,
                QueueStagePayload::KEY_CURRENT_STAGES => [
                    'STAGE1'
                ],
            ]
        ];

        $result = $trait->addNextStageP($payload, 'STAGE2');

        self::assertEquals(
            ['STAGE1', 'STAGE2'],
            $result[QueueStagePayload::KEY][QueueStagePayload::KEY_CURRENT_STAGES]
        );
    }

    /**
     * @param string $pushReturn
     * @return QueueStagePayloadPusher
     */
    private function getPusherMock(string $pushReturn): QueueStagePayloadPusher
    {
        /** @var QueueStagePayloadPusher|MockObject $pusher */
        $pusher = $this->createMock(QueueStagePayloadPusher::class);
        $pusher->method('push')->willReturn($pushReturn);

        return $pusher;
    }
}
