#!/usr/bin/env php
<?php


// load elevator Comamnd file
// perform some action

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Helper\QuestionHelper;

require_once __DIR__ . '/../vendor/autoload.php';

(new SingleCommandApplication())
    ->setName('My Super Command') // Optional
    ->setVersion('1.0.0') // Optional
    ->addArgument('foo', InputArgument::OPTIONAL, 'The directory')
    ->addOption('bar', null, InputOption::VALUE_REQUIRED)
    ->setCode(function (InputInterface $input, OutputInterface $output) {

        $liftController = new \ElevatorKata\LiftController(
            new \ElevatorKata\Lift()
        );


        while (true) {
            $currentFloorQuestion = "Which floor are you on?";

            $desiredFloorQuestionText = "Which floor do you want?";

            $helper = new QuestionHelper();


            $whichFloorAreYouOn = new ChoiceQuestion($currentFloorQuestion, range(1, 10));

            $currentFloor = $helper->ask($input, $output, $whichFloorAreYouOn);

            $desiredFloorQuestion = new ChoiceQuestion($desiredFloorQuestionText, range(1, 10));

            $desiredFloor = $helper->ask($input, $output, $desiredFloorQuestion);


            $progressBar = new ProgressBar($output, 10);

            $progressBar->start();
            $progressBar->setProgress($currentFloor);

            $i = $currentFloor;
            while ($i-- >= $desiredFloor) {
                // ... do some work

                // advances the progress bar 1 unit
                $progressBar->advance(-1);

                usleep(1000000);

                // you can also advance the progress bar by more than 1 unit
                // $progressBar->advance(3);
            }


            $progressBar->finish();

            $output->writeln('');

        }


    })
    ->run();
