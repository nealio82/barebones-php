<?php

declare(strict_types=1);

namespace ElevatorKata;

class LiftController
{
    private Lift $lift;

    private array $calls = [];

    public function __construct(Lift $lift)
    {
        $this->lift = $lift;
    }

    public function receiveCall(int $callingFloor, DesiredDirection $direction): void
    {
        $this->calls[$callingFloor] = $direction;
    }

    public function tick(): string
    {
        ksort($this->calls);
        foreach ($this->calls as $floor => $direction) {
            return $this->recordEventsForMovingToFloor($floor, $this->lift->currentFloor())->current() ?? '';
        }
    }

    private function recordEventsForMovingToFloor(int $desiredFloor, int $currentFloor): \Generator
    {
        $numberOfFloorsToMove = $desiredFloor - $currentFloor;

        $absoluteNumberOfFloorsToMove = abs($numberOfFloorsToMove);

        for ($i = 0; $i < $absoluteNumberOfFloorsToMove; $i++) {
            yield from $this->lift->moveUp();
        }

        if (array_key_exists($this->lift->currentFloor(), $this->calls)) {
            unset($this->calls[$this->lift->currentFloor()]);
            yield from $this->lift->openDoors();
        }

        // todo: change function name
        // todo: implement moving down
        // todo: implement passenger inside the elevator clicking the floor they want to go
    }
}
