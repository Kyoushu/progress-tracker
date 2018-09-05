<?php

namespace Kyoushu;

use Kyoushu\ProgressTracker\Formatter\ProgressBarFormatter;
use Kyoushu\ProgressTracker\TaskTracker;

require(__DIR__ . '/../vendor/autoload.php');

$items = [
    [
        'name' => '1',
        'sub_items' => [
            ['name' => '1.1'],
            ['name' => '1.2'],
            ['name' => '1.3'],
            ['name' => '1.4'],
            ['name' => '1.5'],
            ['name' => '1.6'],
            ['name' => '1.7'],
            ['name' => '1.8'],
        ]
    ],
    [
        'name' => '2',
        'sub_items' => [
            ['name' => '2.1'],
            ['name' => '2.2'],
            ['name' => '2.3'],
            ['name' => '2.4'],
            ['name' => '2.5'],
            ['name' => '2.6'],
        ]
    ],
    [
        'name' => '3',
        'sub_items' => [
            ['name' => '3.1'],
            ['name' => '3.2'],
            ['name' => '3.3'],
            ['name' => '3.4'],
            ['name' => '3.5'],
            ['name' => '3.6'],
            ['name' => '3.7'],
            ['name' => '3.8'],
            ['name' => '3.9'],
        ]
    ]
];

// First we define all the tasks and sub-tasks so we know the total amount of work involved

$progress = new TaskTracker();
foreach($items as $item){
    $itemTask = $progress->defineSubTask($item['name'])->findSubTask($item['name']);
    foreach($item['sub_items'] as $subItem){
        $itemTask->defineSubTask($subItem['name']);
    }
}

// Calling start() allows us to calculate an ETA as we go

$progress->start();

echo "\n";

$formatter = new ProgressBarFormatter(30, 2);

$lastLine = null;

foreach($items as $item){

    $itemTask = $progress->findSubTask($item['name']);

    foreach($item['sub_items'] as $subItem){

        $subItemTask = $itemTask->findSubTask($subItem['name']);

        // Simulate something taking 100-500ms, then mark the sub-task as done

        usleep(1000 * rand(100,500));
        $subItemTask->setCompleted(true);

        // Print the total percentage completed, and ETA
        if($lastLine !== null) echo str_repeat(chr(8), mb_strlen($lastLine)); // Backspace over last output
        $lastLine = sprintf("%s - %s - ETA: %s", str_pad($subItem['name'], 5, ' ', STR_PAD_LEFT), $formatter->format($progress), $progress->getDateTimeEta()->format('H:i:s'));
        echo $lastLine;

    }
}

$progress->end();

echo "\n\n";
echo sprintf("   Started: %s\n", $progress->getDateTimeStarted()->format('H:i:s'));
echo sprintf("     Ended: %s\n", $progress->getDateTimeEnded()->format('H:i:s'));
echo sprintf("   Elapsed: %s seconds\n", $progress->getSecondsElapsed());
echo "\n";

