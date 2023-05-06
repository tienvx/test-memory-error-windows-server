<?php

require __DIR__ . '/../vendor/autoload.php';

use TestMemoryErrorWindowsServer\Runner\ProcessRunner;

$code = file_get_contents(__DIR__ . '/../bin/pact-ffi-headers/pact.h');
$lib = __DIR__ . '/../bin/pact-ffi-lib/pact.dll';
$dir = __DIR__ . '/../pacts';
$publicPath =  __DIR__ . '/../public/';

$process = new ProcessRunner('php', ['-S', 'localhost:7202', '-t', $publicPath]);
$process->run();
\sleep(1); // wait for server to start

$ffi = FFI::cdef($code, $lib);

$ffi->pactffi_init_with_log_level('debug');
$handle = $ffi->pactffi_verifier_new_for_application(null, null);
$ffi->pactffi_verifier_set_provider_info($handle, 'someProvider', 'http', 'localhost', 7202, '/');
$ffi->pactffi_verifier_add_directory_source($handle, $dir);

$error = $ffi->pactffi_verifier_execute($handle);
$ffi->pactffi_verifier_shutdown($handle);

$process->stop();
