<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor\Tests;

use PHPUnit\Framework\TestCase;
use Umirode\PipelineQueueProcessor\QueueStagePayload;

/**
 * Class QueuePayloadTest
 * @package Umirode\PipelineQueueProcessor\Tests
 */
final class QueuePayloadTest extends TestCase
{
    public function testCreateEmpty(): void
    {
        $stagePayload = new QueueStagePayload();

        self::assertNotEmpty($stagePayload->getPipelineIdentifier());
        self::assertIsString($stagePayload->getPipelineIdentifier());
        self::assertEquals(0, $stagePayload->getCurrentStageNumber());
        self::assertNull($stagePayload->getNextStageNumber());
        self::assertNull($stagePayload->getStage());

        $stagePayload->nextStage();
        self::assertEquals(0, $stagePayload->getCurrentStageNumber());

        $stagePayloadArray = $stagePayload->toArray();
        self::assertEquals(
            $stagePayload->getPipelineIdentifier(),
            $stagePayloadArray[QueueStagePayload::KEY_PIPELINE_IDENTIFIER]
        );
        self::assertEquals(
            $stagePayload->getCurrentStageNumber(),
            $stagePayloadArray[QueueStagePayload::KEY_CURRENT_STAGE_NUMBER]
        );
        self::assertEquals([], $stagePayloadArray[QueueStagePayload::KEY_CURRENT_STAGES]);
    }

    public function testCreateFilled(): void
    {
        $stages = [
            QueueStageTraitMock::class,
            QueueStageTraitMock::class,
            QueueStageTraitMock::class,
        ];

        $stagePayload = new QueueStagePayload('test', 0, $stages);

        self::assertEquals('test', $stagePayload->getPipelineIdentifier());
        self::assertEquals(0, $stagePayload->getCurrentStageNumber());
        self::assertEquals(1, $stagePayload->getNextStageNumber());
        self::assertEquals(QueueStageTraitMock::class, $stagePayload->getStage());

        $stagePayload->nextStage();
        self::assertEquals(1, $stagePayload->getCurrentStageNumber());
        self::assertEquals(2, $stagePayload->getNextStageNumber());

        $stagePayloadArray = $stagePayload->toArray();
        self::assertEquals(
            $stagePayload->getPipelineIdentifier(),
            $stagePayloadArray[QueueStagePayload::KEY_PIPELINE_IDENTIFIER]
        );
        self::assertEquals(
            $stagePayload->getCurrentStageNumber(),
            $stagePayloadArray[QueueStagePayload::KEY_CURRENT_STAGE_NUMBER]
        );
        self::assertEquals($stages, $stagePayloadArray[QueueStagePayload::KEY_CURRENT_STAGES]);
    }

    public function testCreateFromArray(): void
    {
        $stagePayload = QueueStagePayload::createFromArray([]);

        self::assertNotEmpty($stagePayload->getPipelineIdentifier());
        self::assertIsString($stagePayload->getPipelineIdentifier());
        self::assertEquals(0, $stagePayload->getCurrentStageNumber());
        self::assertNull($stagePayload->getNextStageNumber());
        self::assertNull($stagePayload->getStage());

        $stagePayload = QueueStagePayload::createFromArray(
            [
                QueueStagePayload::KEY_PIPELINE_IDENTIFIER => 'test',
                QueueStagePayload::KEY_CURRENT_STAGE_NUMBER => 1,
                QueueStagePayload::KEY_CURRENT_STAGES => [
                    QueueStageTraitMock::class,
                    QueueStageTraitMock::class,
                    QueueStageTraitMock::class,
                ],
            ]
        );

        self::assertEquals('test', $stagePayload->getPipelineIdentifier());
        self::assertEquals(1, $stagePayload->getCurrentStageNumber());
        self::assertEquals(2, $stagePayload->getNextStageNumber());
        self::assertEquals(QueueStageTraitMock::class, $stagePayload->getStage());
    }

    public function testAddStage(): void
    {
        $stagePayload = new QueueStagePayload(
            'test',
            0,
            [
                'test1',
                'test2',
                'test3',
            ]
        );

        $stagePayload->addNextStage('test4');

        self::assertEquals('test1', $stagePayload->getStage());

        $stagePayload->nextStage();
        self::assertEquals('test4', $stagePayload->getStage());

        $stagePayload->addNextStage('test5');
        self::assertEquals('test4', $stagePayload->getStage());

        $stagePayload->nextStage();
        self::assertEquals('test5', $stagePayload->getStage());

        self::assertEquals(
            [
                'test1',
                'test4',
                'test5',
                'test2',
                'test3',
            ],
            $stagePayload->toArray()[QueueStagePayload::KEY_CURRENT_STAGES]
        );
    }
}
