<?php

use ElevatorKata\DesiredDirection;
use ElevatorKata\Lift;
use ElevatorKata\LiftController;

class LiftFunctionalTest extends \PHPUnit\Framework\TestCase
{

    public function test_lift_stops_to_collect_subsequent_passengers_in_the_same_direction(): void
    {
        $elevatorStartingFloor = 1;
        $person1StartingFloor = 4;
        $person2StartingFloor = 5;

        $lift = new Lift($elevatorStartingFloor);

        $liftController = new LiftController($lift);


        $liftController->receiveCall($person1StartingFloor, DesiredDirection::UP);
        $liftController->receiveCall($person2StartingFloor, DesiredDirection::UP);
        $liftController->run();
        $this->assertSame(
            [
                'doors closed',
                'lift moved up', // 1st to 2nd floor
                'lift moved up',  // 2nd to 3rd floor
                'lift moved up',  // 3rd to 4th floor
                'doors open', // pick up person 1
                'doors closed',
                'lift moved up', //  4th to 5th floor
                'doors open', // pick up person 2
            ],
            $liftController->getRecordedEvents()
        );
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
        $liftController->run();
        $this->assertSame(
            [
                'doors closed',
                'lift moved up', // 1st to 2nd floor
                'lift moved up',  // 2nd to 3rd floor
                'lift moved up',  // 3rd to 4th floor
                'doors open', // pick up person 2
                'doors closed',
                'lift moved up', //  4th to 5th floor
                'doors open', // pick up person 1
            ],
            $liftController->getRecordedEvents()
        );
    }
}