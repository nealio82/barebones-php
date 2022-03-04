<?php

namespace ElevatorKata;

class Lift
{
    private int $currentFloor;

    private bool $doorsOpen;

    public function __construct(int $currentFloor = 1, bool $doorsOpen = true)
    {
        $this->currentFloor = $currentFloor;
        $this->doorsOpen = $doorsOpen;
    }

    public function currentFloor(): int
    {
        return $this->currentFloor;
    }

    public function areDoorsOpen(): bool
    {
        return $this->doorsOpen;
    }

    public function moveUp(): \Traversable
    {
        if ($this->doorsOpen) {
            yield from $this->closeDoors();
        }

        $this->currentFloor++;

        yield 'lift moved up';
    }

    public function openDoors(): \Traversable
    {
        if ($this->doorsOpen) {
            return;
        }

        $this->doorsOpen = true;

        yield 'doors open';
    }

    public function closeDoors(): \Traversable
    {
        if ($this->doorsOpen === false) {
            return;
        }

        $this->doorsOpen = false;

        yield 'doors closed';
    }

    public function moveDown(): \Traversable
    {
        if ($this->doorsOpen) {
            yield from $this->closeDoors();
        }

        $this->currentFloor--;

        yield 'lift moved down';
    }
}
