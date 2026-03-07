<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;

$credentials = ['email' => 'official@barangay.gov', 'password' => 'password123'];

$ok = Auth::guard('web')->once($credentials);
var_dump($ok);

if ($ok) {
    echo "User ID: " . Auth::id() . "\n";
    echo "User role: " . Auth::user()->role . "\n";
}
