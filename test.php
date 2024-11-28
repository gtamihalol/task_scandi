<?php
try {
    $dsn = "mysql:host=127.0.0.1;dbname=test_task;charset=utf8";
    $username = "user";
    $password = "12345";

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}

