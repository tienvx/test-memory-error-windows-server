<?php

require __DIR__ . '/../vendor/autoload.php';

use TestMemoryErrorWindowsServer\Runner\ProcessRunner;

$stubService = __DIR__ . '/../bin/pact-ruby-standalone/bin/pact-stub-service';
$pactLocation = __DIR__ . '/../pacts/someconsumer-someprovider.json';
$host         = 'localhost';
$port         = 7201;
$endpoint     = 'test';

$process = new ProcessRunner($stubService, [$pactLocation, "--host={$host}", "--port={$port}"]);
$process->run();
\sleep(10); // wait for server to start

echo file_get_contents("http://localhost:7201/{$endpoint}");

$process->stop();
