<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'classes/Database.php';

// Подключение к базе данных
$db = (new Database())->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $idsToDelete = $_POST['delete'];

    if (!empty($idsToDelete)) {
        // Создание строки с параметрами для IN (например, ?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));

        // Удаление записей из базы данных
        $stmt = $db->prepare("DELETE FROM products WHERE id IN ($placeholders)");
        $stmt->execute($idsToDelete);
    }
}

// Перенаправление обратно на главную страницу
header('Location: index.php');
exit;
?>
