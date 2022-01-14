<?php

namespace Lift;

use ElevatorKata\Lift;
use PHPUnit\Framework\TestCase;

class LiftTest extends TestCase
{

    public function test_doors_can_be_opened(): void
    {
        $lift = new Lift();


        $this->assertFalse($lift->doorsAreOpen());
        $lift->openDoors();
        $this->assertTrue($lift->doorsAreOpen());
    }

    public function test_lift_is_created_on_ground_floor(): void
    {
        $lift = new Lift();

        $this->assertSame(1, $lift->currentFloor());
    }
}