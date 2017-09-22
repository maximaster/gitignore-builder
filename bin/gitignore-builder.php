<?php

use Symfony\Component\Console\Application;
use Maximaster\Composer\Plugin\GitignoreBuilder\Command;

$found = false;
$nextDir = __DIR__;
while (($dir = $nextDir) && !($dir === '/' || ($nextDir = dirname($dir)) === $dir)) {
    if (!is_dir($vendorDir = "{$dir}/vendor")) {
        continue;
    }

    /** @noinspection PhpIncludeInspection */
    include_once "{$vendorDir}/autoload.php";
    $found = true;
    break;
}

if (!$found) {
    die("Can't find composer autoload");
}

$command = new Command;
$app = new Application($command->getName());
$app->add($command);
$app->setDefaultCommand($command->getName());
$app->run();
