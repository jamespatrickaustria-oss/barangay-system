<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\User::all(['id', 'email', 'role', 'status']) as $u) {
    echo "id={$u->id} email={$u->email} role={$u->role} status={$u->status}\n";
}
