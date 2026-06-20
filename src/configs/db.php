<?php
// src/configs/db.php
// Safe and secure configuration loader for LaundryWiki database.
// Allows overrides via environment variables.
// Default host is set to 127.0.0.1 to avoid local Unix socket connection issues (e.g. on XAMPP/LAMPP).

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'db_name' => getenv('DB_NAME') ?: 'db_laundwiki',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: ''
];
