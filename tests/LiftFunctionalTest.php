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

        $liftController->receiveCallFromOutside($personStartingFloor, DesiredDirection::UP);

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

        $liftController->receiveCallFromOutside($person1StartingFloor, DesiredDirection::UP);
        $liftController->receiveCallFromOutside($person2StartingFloor, DesiredDirection::UP);

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

        $liftController->receiveCallFromOutside($person1StartingFloor, DesiredDirection::UP);
        $liftController->receiveCallFromOutside($person2StartingFloor, DesiredDirection::UP);

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

    public function test_lift_moves_down(): void
    {
        $elevatorStartingFloor = 10;
        $personStartingFloor = 9;

        $lift = new Lift($elevatorStartingFloor, false);

        $liftController = new LiftController($lift);

        $liftController->receiveCallFromOutside($personStartingFloor, DesiredDirection::UP);

        $expectedEvents = [
            'lift moved down', // 10th to 9th floor
            'doors open', // pick up person 1
        ];

        for ($i = 0; $i < count($expectedEvents); ++$i) {
            $actualEvents[] = $liftController->tick();
        }

        $this->assertSame($expectedEvents, $actualEvents);
    }

    public function test_customer_can_choose_a_destination_floor(): void
    {
        $elevatorStartingFloor = 10;

        $lift = new Lift($elevatorStartingFloor, true);

        $liftController = new LiftController($lift);

        $liftController->pressFloorButton(9);

        $expectedEvents = [
            'doors closed',
            'lift moved down',
            'doors open',
        ];

        for ($i = 0; $i < count($expectedEvents); ++$i) {
            $actualEvents[] = $liftController->tick();
        }

        $this->assertSame($expectedEvents, $actualEvents);
    }

    // @todo next week: test this experience:
        // customer is outside the lift
        // lift is closed on same floor
        // the customer requests lift
        // doors open
        // customer requests floor
        // lift moves
        // doors open


    // todo: lift is above the customer and customer selects a floor (10th - 1st, someone on 5th calls lift to go up/down...)
    // todo: lift is below the customer and customer selects a floor
    // todo: lift is on its way down, customer presses down so lift stops but presses higher floor, lift ignores press and continues down
}
