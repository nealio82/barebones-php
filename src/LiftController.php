<?php

declare(strict_types=1);

namespace ElevatorKata;

class LiftController
{
    private int $callingFloor;
    private DesiredDirection $desiredDirection;
    private Lift $lift;
    /**
     * @var string[]
     */
    private array $recordedEvents;

    public function __construct(Lift $lift)
    {
        $this->lift = $lift;
    }

    public function receiveCall(int $callingFloor, DesiredDirection $direction): void
    {
        $this->callingFloor = $callingFloor;
        $this->desiredDirection = $direction;

        $this->recordEventsForMovingToFloor($callingFloor, $this->lift->currentFloor());
    }

    public function acknowledgedCall(): bool
    {
        return true;
    }

    public function callingFloor(): int
    {
        return $this->callingFloor;
    }

    public function desiredDirection(): DesiredDirection
    {
        return $this->desiredDirection;
    }

    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function moveToFloor(int $desiredFloor): void
    {
        $this->recordedEvents[] = 'doors closed';

        $this->recordEventsForMovingToFloor($desiredFloor, $this->lift->currentFloor());
    }

    private function recordEventsForMovingToFloor(int $desiredFloor, int $currentFloor): void
    {
        $numberOfFloorsToMove = $desiredFloor - $currentFloor;
        $movingDirection = $numberOfFloorsToMove < 0 ? 'down' : 'up';

        $absoluteNumberOfFloorsToMove = abs($numberOfFloorsToMove);

        for ($i = 0; $i < $absoluteNumberOfFloorsToMove; $i++) {
            $this->recordedEvents[] = 'lift moved ' . $movingDirection;
        }

        $this->recordedEvents[] = 'doors open';
    }

    /**
     * @param int[] $requestFloors
     */
    public function moveToMultipleFloors(array $requestFloors): void
    {
        $previousFloor = $this->lift->currentFloor();

        foreach ($requestFloors as $requestFloor) {
            $this->recordedEvents[] = 'doors closed';
            $this->recordEventsForMovingToFloor($requestFloor, $previousFloor);
            $previousFloor = $requestFloor;
        }
    }
}