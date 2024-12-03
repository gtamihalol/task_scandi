<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "Database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['productType'];

    $size = $_POST['size'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $height = $_POST['height'] ?? null;
    $width = $_POST['width'] ?? null;
    $length = $_POST['length'] ?? null;

    $dimensions = $type === 'Furniture' ? $height . 'x' . $width . 'x' . $length : null;

    $database = new Database();
    $conn = $database->getConnection();

    $query = "INSERT INTO products (sku, name, price, type, size_mb, weight_kg, dimensions) 
              VALUES (:sku, :name, :price, :type, :size, :weight, :dimensions)";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':sku', $sku);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':size', $size);
    $stmt->bindParam(':weight', $weight);
    $stmt->bindParam(':dimensions', $dimensions);

    // Проверка выполнения запроса
    if ($stmt->execute()) {
        echo "Product added successfully!";
        header("Location: index.php");
        exit;
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Error: Unable to save product. " . $errorInfo[2];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css"> <!-- Подключите стили -->
</head>

<script src="script.js" defer></script>

<body>
    <header>
        <h1>Add Product</h1>
    </header>
    <main>
        <form id="product_form" method="POST" action="add_product.php">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="sku" required>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="productType">Type Switcher</label>
            <select id="productType" name="productType" required>
                <option value="">Select type</option>
                <option value="DVD">DVD</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
            </select>

            <!-- Поля, которые будут меняться динамически -->
            <div id="type-specific-fields">
                <!-- Динамические поля появятся здесь -->
            </div>

            <button type="submit">Save</button>
            <a href="index.php" class="btn">Cancel</a>
        </form>
    </main>
    <footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
</body>
</html>

