<?php

namespace Kyoushu;

use Kyoushu\ProgressTracker\Formatter\ProgressBarFormatter;
use Kyoushu\ProgressTracker\TaskTracker;

require(__DIR__ . '/../vendor/autoload.php');

$items = [
    [
        'name' => 'Downloading',
        'sub_items' => [
            ['name' => 'core'],
            ['name' => 'packages'],
            ['name' => 'assets'],
            ['name' => 'modules'],
            ['name' => 'source'],
            ['name' => 'libraries'],
            ['name' => 'dependencies'],
            ['name' => 'updates']
        ]
    ],
    [
        'name' => 'Installing',
        'sub_items' => [
            ['name' => 'core'],
            ['name' => 'packages'],
            ['name' => 'assets'],
            ['name' => 'modules'],
            ['name' => 'source'],
            ['name' => 'libraries'],
            ['name' => 'dependencies'],
            ['name' => 'updates']
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

function printProgress($prefix)
{
    global $lastLine, $formatter, $progress;
    if($lastLine !== null) echo str_repeat(chr(8), mb_strlen($lastLine)); // Backspace over last output
    $eta = $progress->getDateTimeEta();
    $lastLine = sprintf("%s - %s - ETA: %s", str_pad($prefix, 25, ' ', STR_PAD_LEFT), $formatter->format($progress), ($eta ? $eta->format('H:i:s') : '-'));
    echo $lastLine;
}

foreach($items as $item){

    $itemTask = $progress->findSubTask($item['name']);

    printProgress($item['name']);

    foreach($item['sub_items'] as $subItem){

        // Simulate an activity taking time, mark sub-item as completed, render progress

        printProgress($item['name'] . ' ' . $subItem['name']);

        usleep(1000 * rand(500,800));

        $subItemTask = $itemTask->findSubTask($subItem['name']);
        $subItemTask->setCompleted(true);

        printProgress($item['name'] . ' ' . $subItem['name']);

        usleep(1000 * rand(500,800));

    }
}

$progress->end();

echo "\n\n";
echo sprintf("   Started: %s\n", $progress->getDateTimeStarted()->format('H:i:s'));
echo sprintf("     Ended: %s\n", $progress->getDateTimeEnded()->format('H:i:s'));
echo sprintf("   Elapsed: %s seconds\n", $progress->getSecondsElapsed());
echo "\n";

