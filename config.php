<?php
// config.php

// Load environment variables from .env file
if (file_exists(__DIR__ . '/.env')) {
    foreach (parse_ini_file(__DIR__ . '/.env') as $key => $value) {
        $_ENV[$key] = $value;
    }
} else {
    die(json_encode(['error' => '.env file not found']));
}

// Create MySQLi connection
$conn = new mysqli(
    $_ENV['DB_HOST'] ?? 'localhost',
    $_ENV['DB_USERNAME'] ?? '',
    $_ENV['DB_PASSWORD'] ?? '',
    $_ENV['DB_NAME'] ?? ''
);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Set charset to UTF-8
$conn->set_charset('utf8mb4');
?>