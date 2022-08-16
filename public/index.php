<?php

$startTime = microtime();

require_once __DIR__ . '/../vendor/autoload.php';

$application = \App\System\Application::getInstance();
$application->run();




