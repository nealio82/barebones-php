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

    public function receiveCallFromOutside(int $callingFloor, DesiredDirection $direction): void
    {
        $this->calls[$callingFloor] = $direction;
    }

    public function pressFloorButton(int $desiredFloor): void
    {
        $this->calls[$desiredFloor] = DesiredDirection::UP;
    }

    public function tick(): string
    {
        ksort($this->calls);

        foreach ($this->calls as $floor => $direction) {
            return $this->calculateRequiredActions($floor, $this->lift->currentFloor())->current() ?? '';
        }
    }

    private function calculateRequiredActions(int $desiredFloor, int $currentFloor): \Generator
    {
        $numberOfFloorsToMove = $desiredFloor - $currentFloor;

        if (isset($this->calls[$currentFloor])) {
            unset($this->calls[$currentFloor]);
            yield from $this->lift->openDoors();
        }

        if ($numberOfFloorsToMove > 0) {
            for ($i = 0; $i < $numberOfFloorsToMove; $i++) {
                yield from $this->lift->moveUp();
            }
        }

        if ($numberOfFloorsToMove < 0) {
            for ($i = $numberOfFloorsToMove; $i < 0; $i++) {
                yield from $this->lift->moveDown();
            }
        }


        if (array_key_exists($this->lift->currentFloor(), $this->calls)) {
            unset($this->calls[$this->lift->currentFloor()]);
            yield from $this->lift->openDoors();
        }
    }
}
