<?php

namespace ElevatorKata;

class Lift
{
    private int $currentFloor;

    public function __construct(int $currentFloor = 1)
    {
        $this->currentFloor = $currentFloor;
    }

    public function currentFloor(): int
    {
        return $this->currentFloor;
    }
}