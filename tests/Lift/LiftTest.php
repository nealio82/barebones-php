<?php

namespace Lift;

use ElevatorKata\Lift;
use PHPUnit\Framework\TestCase;

class LiftTest extends TestCase
{
    public function test_lift_is_created_on_ground_floor(): void
    {
        $lift = new Lift();

        $this->assertSame(1, $lift->currentFloor());
    }

    public function test_lift_is_created_with_doors_open_by_default(): void
    {
        $lift = new Lift();

        $this->assertSame(true, $lift->areDoorsOpen());
    }

    public function test_lift_goes_up_one_floor_when_doors_are_closed(): void
    {
        $lift = new Lift(1, false);

        $this->assertSame(
            [
                'lift moved up',
            ],
            \iterator_to_array($lift->moveUp(), false)
        );
    }

    public function test_lift_goes_up_one_floor_when_doors_are_open(): void
    {
        $lift = new Lift(1, true);

        $this->assertSame(
            [
                'doors closed',
                'lift moved up',
            ],
            \iterator_to_array($lift->moveUp(), false)
        );
    }
}
