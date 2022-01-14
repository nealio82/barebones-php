<?php

namespace ElevatorKata;

class Lift
{
    private bool $doorsAreOpen = false;

    private int $currentFloor = 1;

    public function __construct()
    {
    }

    public function openDoors(): void
    {
        $this->doorsAreOpen = true;
    }

    public function doorsAreOpen(): bool
    {
        return $this->doorsAreOpen;
    }

    public function currentFloor(): int
    {
        return $this->currentFloor;
    }
}