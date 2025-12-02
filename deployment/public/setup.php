<?php
// setup.php - Run this once after uploading files
// Place this in public folder and access via browser

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h1>Laravel Setup Script</h1>";

// Check if .env exists
if (!file_exists(__DIR__.'/../.env')) {
    copy(__DIR__.'/../.env.production', __DIR__.'/../.env');
    echo "<p>✓ .env file created</p>";
}

// Generate key if not exists
if (empty(env('APP_KEY'))) {
    Artisan::call('key:generate');
    echo "<p>✓ Application key generated</p>";
}

// Run migrations
try {
    Artisan::call('migrate', ['--force' => true]);
    echo "<p>✓ Database migrations completed</p>";
} catch (Exception $e) {
    echo "<p>✗ Migration error: " . $e->getMessage() . "</p>";
}

// Create storage link
try {
    Artisan::call('storage:link');
    echo "<p>✓ Storage link created</p>";
} catch (Exception $e) {
    echo "<p>✗ Storage link error: " . $e->getMessage() . "</p>";
}

// Clear caches
Artisan::call('config:cache');
Artisan::call('route:cache');
Artisan::call('view:cache');
echo "<p>✓ Caches cleared</p>";

// Create admin user
try {
    $user = \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@longon.org',
        'password' => Hash::make('admin123456'),
        'user_type' => 'admin',
        'credits_balance' => 100,
        'email_verified_at' => now(),
    ]);
    echo "<p>✓ Admin user created (email: admin@longon.org, password: admin123456)</p>";
} catch (Exception $e) {
    echo "<p>ℹ Admin user already exists or error: " . $e->getMessage() . "</p>";
}

echo "<h2>Setup Complete!</h2>";
echo "<p><strong>IMPORTANT:</strong> Delete this setup.php file immediately!</p>";
echo "<p><a href='/'>Go to Homepage</a></p>";
