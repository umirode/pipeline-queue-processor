<?php

declare(strict_types=1);

namespace Umirode\PipelineQueueProcessor;

use Ramsey\Uuid\Uuid;

/**
 * Class QueueStagePayload
 * @package Umirode\PipelineQueueProcessor
 */
final class QueueStagePayload
{
    public const KEY = '__stage_payload__';

    public const KEY_PIPELINE_IDENTIFIER = self::KEY . '__pipeline_identifier__';
    public const KEY_CURRENT_STAGE_NUMBER = self::KEY . 'current_stage_number';
    public const KEY_CURRENT_STAGES = self::KEY . 'stages';

    /**
     * @var string
     */
    private $pipelineIdentifier;

    /**
     * @var int
     */
    private $currentStageNumber;

    /**
     * @var array
     */
    private $stages;

    /**
     * QueueStagePayload constructor.
     * @param string|null $pipelineIdentifier
     * @param int $currentStageNumber
     * @param array $stages
     */
    public function __construct(?string $pipelineIdentifier = null, int $currentStageNumber = 0, array $stages = [])
    {
        $this->pipelineIdentifier = $pipelineIdentifier ?? Uuid::uuid4()->toString();
        $this->currentStageNumber = $currentStageNumber;
        $this->stages = $stages;
    }

    /**
     * @return string
     */
    public function getPipelineIdentifier(): string
    {
        return $this->pipelineIdentifier;
    }

    /**
     * @return int
     */
    public function getCurrentStageNumber(): int
    {
        return $this->currentStageNumber;
    }

    /**
     * @return int|null
     */
    public function getNextStageNumber(): ?int
    {
        $nextStageNumber = $this->currentStageNumber + 1;

        return isset($this->stages[$nextStageNumber]) ? $nextStageNumber : null;
    }

    /**
     * @return string|null
     */
    public function getStage(): ?string
    {
        return $this->stages[$this->currentStageNumber] ?? null;
    }

    /**
     * @param string $stage
     */
    public function addNextStage(string $stage): void
    {
        $firstStagesPart = array_slice($this->stages, 0, $this->currentStageNumber + 1);
        $secondStagesPart = array_slice($this->stages, $this->currentStageNumber + 1);

        $firstStagesPart [] = $stage;

        $this->stages = array_merge($firstStagesPart, $secondStagesPart);
    }

    /**
     * @param int $currentStageNumber
     */
    public function setCurrentStageNumber(int $currentStageNumber): void
    {
        $this->currentStageNumber = $currentStageNumber;
    }

    public function nextStage(): void
    {
        $nextStageNumber = $this->getNextStageNumber();
        if ($nextStageNumber === null) {
            return;
        }

        $this->setCurrentStageNumber($nextStageNumber);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_PIPELINE_IDENTIFIER => $this->pipelineIdentifier,
            self::KEY_CURRENT_STAGE_NUMBER => $this->currentStageNumber,
            self::KEY_CURRENT_STAGES => $this->stages,
        ];
    }

    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data): self
    {
        return new static(
            $data[self::KEY_PIPELINE_IDENTIFIER] ?? null,
            $data[self::KEY_CURRENT_STAGE_NUMBER] ?? 0,
            $data[self::KEY_CURRENT_STAGES] ?? []
        );
    }
}
