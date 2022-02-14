<?php

namespace ElevatorKata;

class Lift
{
    private int $currentFloor;

    private string $doorsState;

    public function __construct(int $currentFloor = 1)
    {
        $this->currentFloor = $currentFloor;
    }

    public function currentFloor(): int
    {
        return $this->currentFloor;
    }

    public function moveUp(): \Traversable
    {
        // @todo: make doors closed an invariant on this
        $this->currentFloor++;

        yield 'lift moved up';
    }

    public function openDoors(): string
    {
        // @todo ignore if doors already open
        $this->doorsState = 'open';

        return 'Doors open';
    }

    public function closeDoors(): string
    {
        // @todo ignore if doors already closed
        $this->doorsState = 'closed';

        return 'Doors closed';
    }
}