<?php


namespace Umirode\PipelineQueueProcessor;


use Ramsey\Uuid\Uuid;

/**
 * Class QueueStagePayload
 * @package Umirode\PipelineQueueProcessor
 */
final class QueueStagePayload
{
    public const KEY = '__stage_payload__';

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
        $this->pipelineIdentifier = $pipelineIdentifier ?? (string)Uuid::uuid4();
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
        return isset($this->stages[$this->currentStageNumber + 1]) ? $this->currentStageNumber + 1 : null;
    }

    /**
     * @return string|null
     */
    public function getStage(): ?string
    {
        return $this->stages[$this->currentStageNumber] ?? null;
    }

    /**
     * @param int $currentStageNumber
     */
    public function setCurrentStageNumber(int $currentStageNumber): void
    {
        $this->currentStageNumber = $currentStageNumber;
    }

    public function nextStage(): void {
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
            'pipeline_identifier' => $this->pipelineIdentifier,
            'current_stage_number' => $this->currentStageNumber,
            'stages' => $this->stages,
        ];
    }

    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data): self
    {
        return new static(
            $data['pipeline_identifier'] ?? null,
            $data['current_stage_number'] ?? 0,
            $data['stages'] ?? []
        );
    }
}
