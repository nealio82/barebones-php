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


test('an elevator responds to calls containing a intended direction', function (DesiredDirection $direction) {
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

    $this->assertTrue($lift->doorsAreOpen());
});

test('the doors are not opened if the lift is on a different floor', function () {

    $lift = new Lift();

    $liftController = new LiftController($lift);

    $liftController->receiveCall(2, DesiredDirection::UP);

    $this->assertFalse($lift->doorsAreOpen());
});


test('the lift moves to the calling floor and opens the doors', function () {

    // @todo
    // we were talking about 'ticks' vs 'cost' as a way of managing time-aware behaviours on the lift

    $lift = new Lift();

    $liftController = new LiftController($lift);

    $liftController->receiveCall(2, DesiredDirection::UP);

    $this->assertFalse($lift->doorsAreOpen());


//    [
        // move lift,
        // open doors
//    ]

});

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