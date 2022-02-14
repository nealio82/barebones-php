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

    private array $calls = [];

    public function __construct(Lift $lift)
    {
        $this->lift = $lift;
    }

    public function receiveCall(int $callingFloor, DesiredDirection $direction): void
    {
        $this->callingFloor = $callingFloor;
        $this->desiredDirection = $direction;

        $this->calls[$callingFloor] = $direction;
    }

    public function run(): void
    {
        ksort($this->calls);

        foreach ($this->calls as $floor => $direction) {
            $this->recordEventsForMovingToFloor($floor, $this->lift->currentFloor());
        }

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

        $this->recordedEvents[] = 'doors closed';

        for ($i = 0; $i < $absoluteNumberOfFloorsToMove; $i++) {

            $this->recordedEvents = array_merge(
                $this->recordedEvents,
                \iterator_to_array($this->lift->moveUp())
            );


            if (array_key_exists($currentFloor + $i, $this->calls) && $this->calls[$currentFloor + $i] === DesiredDirection::UP) {
                $this->lift->openDoors();
                $this->lift->closeDoors();
                unset($this->calls[$currentFloor + $i]);
            }
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