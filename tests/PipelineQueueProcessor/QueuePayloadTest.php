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

        $this->assertNotEmpty($stagePayload->getPipelineIdentifier());
        $this->assertIsString($stagePayload->getPipelineIdentifier());
        $this->assertEquals(0, $stagePayload->getCurrentStageNumber());
        $this->assertNull($stagePayload->getNextStageNumber());
        $this->assertNull($stagePayload->getStage());

        $stagePayload->nextStage();
        $this->assertEquals(0, $stagePayload->getCurrentStageNumber());

        $stagePayloadArray = $stagePayload->toArray();
        $this->assertEquals($stagePayload->getPipelineIdentifier(), $stagePayloadArray['pipeline_identifier']);
        $this->assertEquals($stagePayload->getCurrentStageNumber(), $stagePayloadArray['current_stage_number']);
        $this->assertEquals([], $stagePayloadArray['stages']);
    }

    public function testCreateFilled(): void
    {
        $stages = [
            TestStage::class,
            TestStage::class,
            TestStage::class,
        ];

        $stagePayload = new QueueStagePayload('test', 0, $stages);

        $this->assertEquals('test', $stagePayload->getPipelineIdentifier());
        $this->assertEquals(0, $stagePayload->getCurrentStageNumber());
        $this->assertEquals(1, $stagePayload->getNextStageNumber());
        $this->assertEquals(TestStage::class, $stagePayload->getStage());

        $stagePayload->nextStage();
        $this->assertEquals(1, $stagePayload->getCurrentStageNumber());
        $this->assertEquals(2, $stagePayload->getNextStageNumber());

        $stagePayloadArray = $stagePayload->toArray();
        $this->assertEquals($stagePayload->getPipelineIdentifier(), $stagePayloadArray['pipeline_identifier']);
        $this->assertEquals($stagePayload->getCurrentStageNumber(), $stagePayloadArray['current_stage_number']);
        $this->assertEquals($stages, $stagePayloadArray['stages']);
    }

    public function testCreateFromArray(): void
    {
        $stagePayload = QueueStagePayload::createFromArray([]);

        $this->assertNotEmpty($stagePayload->getPipelineIdentifier());
        $this->assertIsString($stagePayload->getPipelineIdentifier());
        $this->assertEquals(0, $stagePayload->getCurrentStageNumber());
        $this->assertNull($stagePayload->getNextStageNumber());
        $this->assertNull($stagePayload->getStage());

        $stagePayload = QueueStagePayload::createFromArray(
            [
                'pipeline_identifier' => 'test',
                'current_stage_number' => 1,
                'stages' => [
                    TestStage::class,
                    TestStage::class,
                    TestStage::class,
                ],
            ]
        );

        $this->assertEquals('test', $stagePayload->getPipelineIdentifier());
        $this->assertEquals(1, $stagePayload->getCurrentStageNumber());
        $this->assertEquals(2, $stagePayload->getNextStageNumber());
        $this->assertEquals(TestStage::class, $stagePayload->getStage());
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

        $this->assertEquals('test1', $stagePayload->getStage());

        $stagePayload->nextStage();
        $this->assertEquals('test4', $stagePayload->getStage());

        $stagePayload->addNextStage('test5');
        $this->assertEquals('test4', $stagePayload->getStage());

        $stagePayload->nextStage();
        $this->assertEquals('test5', $stagePayload->getStage());

        $this->assertEquals(
            [
                'test1',
                'test4',
                'test5',
                'test2',
                'test3',
            ],
            $stagePayload->toArray()['stages']
        );
    }
}
