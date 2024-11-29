<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'classes/Database.php';

$db = (new Database())->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $idsToDelete = $_POST['delete'];

    if (!empty($idsToDelete)) {
        $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));

        $stmt = $db->prepare("DELETE FROM products WHERE id IN ($placeholders)");
        $stmt->execute($idsToDelete);
    }
}

header('Location: index.php');
exit;
?>
