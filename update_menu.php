<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$m = \App\Models\Menu::where('label', 'LIKE', '%Penerbitan Buku%')->first();
if ($m) {
    echo "Found menu item: " . $m->label . " (ID: " . $m->id . ") with URL: " . $m->url . "\n";
    $m->url = '/katalog-buku';
    // wait, what's the path for books index?
    // Let me check routes to be sure.
} else {
    echo "Menu not found.\n";
}
