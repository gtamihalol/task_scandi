<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'classes/Database.php';
require_once 'classes/DVD.php';
require_once 'classes/Book.php';
require_once 'classes/Furniture.php';

// Подключение к базе данных
$db = (new Database())->connect();

// Извлечение всех продуктов из базы данных
$stmt = $db->query("SELECT * FROM products ORDER BY id ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>

<style>
	h1 {
	display: none;
	}
</style>

<body>
    <header>
        <h1>Product List</h1>
	<h2>Product List</h2>
        <div>
            <button onclick="location.href='addproduct.php'">ADD</button>
            <button id="delete-product-btn" form="product-list-form">MASS DELETE</button>
        </div>
    </header>
    <main>
        <form id="product-list-form" method="POST" action="delete-products.php">
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <input type="checkbox" name="delete[]" class="delete-checkbox" value="<?= $product['id'] ?>">
                        <p>SKU: <?= htmlspecialchars($product['sku']) ?></p>
                        <p>Name: <?= htmlspecialchars($product['name']) ?></p>
                        <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
                        <?php if ($product['product_type'] === 'DVD'): ?>
                            <p>Size: <?= htmlspecialchars($product['size_mb']) ?> MB</p>
                        <?php elseif ($product['product_type'] === 'Book'): ?>
                            <p>Weight: <?= htmlspecialchars($product['weight_kg']) ?> Kg</p>
                        <?php elseif ($product['product_type'] === 'Furniture'): ?>
                            <p>Dimensions: <?= htmlspecialchars($product['dimensions']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    </main>
    <script>
        document.getElementById('delete-product-btn').addEventListener('click', function () {
            if (confirm("Are you sure you want to delete selected products?")) {
                document.getElementById('product-list-form').submit();
            }
        });
    </script>
</body>
</html>


