<?php

// Laravel Vercel bootstrap loader
require_once __DIR__ . '/../vendor/autoload.php';

// Create the application instance
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);
