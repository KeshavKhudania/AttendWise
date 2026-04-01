<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

$faculty = DB::table('institution_faculties')->first();
echo "Encrypted Email: " . $faculty->email . "\n";
echo "Email Hash: " . $faculty->email_hash . "\n";

try {
    $decrypted = Crypt::decryptString($faculty->email);
    echo "Decrypted Email: " . $decrypted . "\n";
} catch (\Exception $e) {
    echo "Decryption FAILED: " . $e->getMessage() . "\n";
}
