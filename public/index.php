<?php
require_once '../vendor/autoload.php';
require_once '../app/helpers.php';
require_once '../app/routes.php';

$start_time = microtime(true);
$memory_start = memory_get_usage();

$response = \Mike\Bnovo\Application::run()->send();

$end_time = microtime(true);
$execution_time = round(($end_time - $start_time) * 1000, 2);
$memory_usage = round((memory_get_usage() - $memory_start) / 1024, 2);

header("X-Debug-Time: $execution_time ms");
header("X-Debug-Memory: $memory_usage KB");

echo $response;