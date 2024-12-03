<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Database.php';

// Если форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = (new Database())->connect();

        $sku = $_POST['sku'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $productType = $_POST['productType'];

        // Специфичные данные для типов
        $size = $productType === 'DVD' ? $_POST['size'] : null;
        $weight = $productType === 'Book' ? $_POST['weight'] : null;
        $dimensions = $productType === 'Furniture' ? $_POST['height'] . 'x' . $_POST['width'] . 'x' . $_POST['length'] : null;

        // Вставка в базу данных
        $stmt = $db->prepare("
            INSERT INTO products (sku, name, price, product_type, size_mb, weight_kg, dimensions)
            VALUES (:sku, :name, :price, :productType, :size, :weight, :dimensions)
        ");
        $stmt->execute([
            ':sku' => $sku,
            ':name' => $name,
            ':price' => $price,
            ':productType' => $productType,
            ':size' => $size,
            ':weight' => $weight,
            ':dimensions' => $dimensions
        ]);

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/scripts.js" defer></script>
</head>
<body>
    <header>
        <h1>Product Add</h1>
        <div>
            <button form="product_form" type="submit">Save</button>
            <button onclick="location.href='index.php'">Cancel</button>
        </div>
    </header>
    <main>
        <form id="product_form" method="POST" action="">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="sku" required>
            
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            
            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" required>
            
            <label for="productType">Type Switcher</label>
            <select id="productType" name="productType" required>
                <option value="" disabled selected>Select Type</option>
                <option value="DVD">DVD</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
            </select>
            
            <!-- DVD-specific -->
            <div id="DVD" class="type-specific">
                <label for="size">Size (MB)</label>
                <input type="number" id="size" name="size">
            </div>
            
            <!-- Book-specific -->
            <div id="Book" class="type-specific">
                <label for="weight">Weight (KG)</label>
                <input type="number" id="weight" name="weight">
            </div>
            
            <!-- Furniture-specific -->
            <div id="Furniture" class="type-specific">
                <label for="height">Height (CM)</label>
                <input type="number" id="height" name="height">
                <label for="width">Width (CM)</label>
                <input type="number" id="width" name="width">
                <label for="length">Length (CM)</label>
                <input type="number" id="length" name="length">
            </div>
        </form>
    </main>
</body>
</html>
