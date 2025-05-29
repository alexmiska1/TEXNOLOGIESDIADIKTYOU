<?php
// Database connection (PDO)
$db = new PDO(
    'mysql:host=db;dbname=mydatabase;charset=utf8mb4',
    'user',
    'userpass',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]
);
