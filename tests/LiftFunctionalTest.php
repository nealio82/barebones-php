<?php

use ElevatorKata\DesiredDirection;
use ElevatorKata\Lift;
use ElevatorKata\LiftController;

class LiftFunctionalTest extends \PHPUnit\Framework\TestCase
{
    public function test_lift_stops_to_collect_a_passenger(): void
    {
        $elevatorStartingFloor = 1;
        $personStartingFloor = 3;

        $lift = new Lift($elevatorStartingFloor, true);

        $liftController = new LiftController($lift);

        $liftController->receiveCall($personStartingFloor, DesiredDirection::UP);

        $expectedEvents = [
            'doors closed',
            'lift moved up', // 1st to 2nd floor
            'lift moved up',  // 2nd to 3rd floor
            'doors open', // pick up person 1
        ];

        for ($i = 0; $i < count($expectedEvents); ++$i) {
            $actualEvents[] = $liftController->tick();
        }

        $this->assertSame($expectedEvents, $actualEvents);
    }

    public function test_lift_stops_to_collect_subsequent_passengers_in_the_same_direction(): void
    {
        $elevatorStartingFloor = 1;
        $person1StartingFloor = 4;
        $person2StartingFloor = 5;

        $lift = new Lift($elevatorStartingFloor);

        $liftController = new LiftController($lift);

        $liftController->receiveCall($person1StartingFloor, DesiredDirection::UP);
        $liftController->receiveCall($person2StartingFloor, DesiredDirection::UP);

        $expectedEvents = [
            'doors closed',
            'lift moved up', // 1st to 2nd floor
            'lift moved up',  // 2nd to 3rd floor
            'lift moved up',  // 3rd to 4th floor
            'doors open', // pick up person 1
            'doors closed',
            'lift moved up', //  4th to 5th floor
            'doors open', // pick up person 2
        ];

        for ($i = 0; $i < count($expectedEvents); ++$i) {
            $actualEvents[] = $liftController->tick();
        }

        $this->assertSame($expectedEvents, $actualEvents);
    }

    public function test_lift_optimises_collecting_subsequent_passengers_in_the_same_direction(): void
    {
        $elevatorStartingFloor = 1;
        $person1StartingFloor = 5;
        $person2StartingFloor = 4;

        $lift = new Lift($elevatorStartingFloor);

        $liftController = new LiftController($lift);

        $liftController->receiveCall($person1StartingFloor, DesiredDirection::UP);
        $liftController->receiveCall($person2StartingFloor, DesiredDirection::UP);

        $expectedEvents = [
            'doors closed',
            'lift moved up', // 1st to 2nd floor
            'lift moved up',  // 2nd to 3rd floor
            'lift moved up',  // 3rd to 4th floor
            'doors open', // pick up person 1
            'doors closed',
            'lift moved up', //  4th to 5th floor
            'doors open', // pick up person 2
        ];

        for ($i = 0; $i < count($expectedEvents); ++$i) {
            $actualEvents[] = $liftController->tick();
        }

        $this->assertSame($expectedEvents, $actualEvents);
    }
}
