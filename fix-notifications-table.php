<?php

// This script creates the notifications table with appropriate structure
// Use this to fix the notification table structure

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    // Fix the notifications table structure
    \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS notifications');
    
    \Illuminate\Support\Facades\DB::statement('
        CREATE TABLE IF NOT EXISTS notifications (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED,
            case_id CHAR(26),
            type VARCHAR(50) NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            data JSON NULL,
            read_at TIMESTAMP NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            INDEX notifications_user_id_index(user_id),
            INDEX notifications_case_id_index(case_id),
            INDEX notifications_read_at_index(read_at),
            INDEX notifications_created_at_index(created_at)
        ) ENGINE=InnoDB;
    ');
    
    echo "✅ Notifications table created successfully!\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating notifications table: " . $e->getMessage() . "\n";
}