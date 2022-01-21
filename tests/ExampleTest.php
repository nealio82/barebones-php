<?php


use ElevatorKata\DesiredDirection;
use ElevatorKata\Lift;
use ElevatorKata\LiftController;

test('an elevator responds to calls containing a calling floor', function (int $floor) {
    $liftController = new LiftController(new Lift());

    $liftController->receiveCall($floor, DesiredDirection::UP);

    $this->assertTrue($liftController->acknowledgedCall());
    $this->assertSame($floor, $liftController->callingFloor());
})->with([
    1,
    2
]);


test('lift responds to calls containing a intended direction', function (DesiredDirection $direction) {
    $liftController = new LiftController(new Lift());

    $liftController->receiveCall(1, $direction);

    $this->assertTrue($liftController->acknowledgedCall());
    $this->assertSame($direction, $liftController->desiredDirection());
})->with([
    DesiredDirection::UP,
    DesiredDirection::DOWN,
]);


test('the doors are opened if the lift is already on the calling floor', function () {

    $lift = new Lift();

    $liftController = new LiftController($lift);

    $liftController->receiveCall(1, DesiredDirection::UP);

    $this->assertSame(
        [
            'doors open',
        ],
        $liftController->getRecordedEvents()
    );
});

test('the doors are not opened if the lift is on a different floor', function () {
    $lift = new Lift();

    $liftController = new LiftController($lift);

    $liftController->receiveCall(2, DesiredDirection::UP);

    $this->assertNotSame('doors open', $liftController->getRecordedEvents()[0]);
});


test('the lift moves to the calling floor and opens the doors', function () {

    // @todo
    // we were talking about 'ticks' vs 'cost' as a way of managing time-aware behaviours on the lift

    $lift = new Lift();

    $liftController = new LiftController($lift);

    $liftController->receiveCall(2, DesiredDirection::UP);

    $this->assertSame(
        [
            'lift moved up',
            'doors open',
        ],
        $liftController->getRecordedEvents()
    );
});

test('the lift moves up 3 floors and opens the doors', function () {
    $lift = new Lift();

    $liftController = new LiftController($lift);

    $liftController->receiveCall(4, DesiredDirection::UP);

    $this->assertSame(
        [
            'lift moved up',
            'lift moved up',
            'lift moved up',
            'doors open',
        ],
        $liftController->getRecordedEvents()
    );
});

test('the lift moves down 3 floors and opens the doors', function () {
    $lift = new Lift(4);

    $liftController = new LiftController($lift);

    $liftController->receiveCall(1, DesiredDirection::UP);

    $this->assertSame(
        [
            'lift moved down',
            'lift moved down',
            'lift moved down',
            'doors open',
        ],
        $liftController->getRecordedEvents()
    );
});

test('lift closes doors and moves up to the requested floor', function () {
    $lift = new Lift(1);

    $liftController = new LiftController($lift);

    $liftController->moveToFloor(4);

    $this->assertSame(
        [
            'doors closed',
            'lift moved up',
            'lift moved up',
            'lift moved up',
            'doors open',
        ],
        $liftController->getRecordedEvents()
    );
});

test('lift closes doors and moves down to the requested floor', function () {
    $lift = new Lift(4);

    $liftController = new LiftController($lift);

    $liftController->moveToFloor(2);

    $this->assertSame(
        [
            'doors closed',
            'lift moved down',
            'lift moved down',
            'doors open',
        ],
        $liftController->getRecordedEvents()
    );
});

test('lift closes doors and moves down to multiple requested floors', function () {
    $lift = new Lift(3);

    $liftController = new LiftController($lift);

    $liftController->moveToMultipleFloors([2, 1]);

    $this->assertSame(
        [
            'doors closed',
            'lift moved down',
            'doors open',
            'doors closed',
            'lift moved down',
            'doors open',
        ],
        $liftController->getRecordedEvents()
    );
});

// @todo next step : introduce an event loop to cover more complex cases
// example : customer 1 goes to floor 1 from floor 4
//           customer 2 calls the lift in meantime

// https://gist.github.com/mattflo/4669508
//These are some features. They can be implemented in any order you prefer.
//
//* an elevator responds to calls containing a source floor and direction
//* an elevator delivers passengers to requested floors
//* an elevator doesn't respond immediately. consider options to simulate time
//* elevator calls are queued not necessarily FIFO
//* you may validate passenger floor requests
//* you may implement current floor monitor
//* you may implement direction arrows
//* you may implement doors (opening and closing)
//* you may implement DING!
//* there can be more than one elevator