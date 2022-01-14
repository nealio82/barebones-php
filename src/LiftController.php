<?php

declare(strict_types=1);

namespace ElevatorKata;

class LiftController
{

    private int $callingFloor;
    private DesiredDirection $desiredDirection;
    private Lift $lift;

    public function __construct(Lift $lift)
    {
        $this->lift = $lift;
    }

    public function receiveCall(int $callingFloor, DesiredDirection $direction): void
    {
        $this->callingFloor = $callingFloor;
        $this->desiredDirection = $direction;

        if ($this->lift->currentFloor() === $callingFloor) {
            $this->lift->openDoors();
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
}