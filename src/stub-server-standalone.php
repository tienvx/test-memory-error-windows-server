<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

$stubService = __DIR__ . '/../bin/pact-ruby-standalone/bin/pact-stub-service';
$pactLocation = __DIR__ . '/../pacts/someconsumer-someprovider.json';
$host         = 'localhost';
$port         = 7201;
$endpoint     = 'test';

$process = new Process([$stubService, $pactLocation, "--host={$host}", "--port={$port}"]);
$process->start();
$process->waitUntil(function (string $type, string $output) {
    return false !== \strpos($output, 'HTTPServer#start');
});

echo file_get_contents("http://localhost:7201/{$endpoint}");

$process->stop();
