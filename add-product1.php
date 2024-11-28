<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'classes/Database.php';

// Подключение к базе данных
$db = (new Database())->connect();

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = trim($_POST['sku']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $productType = $_POST['productType'];
    
    // Валидация
    if (empty($sku) || empty($name) || empty($price) || !is_numeric($price)) {
        $error = 'Please fill in all required fields and ensure the price is a number.';
    } else {
        // Дополнительная валидация по типу продукта
        if ($productType === 'DVD' && empty($_POST['size'])) {
            $error = 'Please provide size for DVD product.';
        } elseif ($productType === 'Book' && empty($_POST['weight'])) {
            $error = 'Please provide weight for Book product.';
        } elseif ($productType === 'Furniture' && (empty($_POST['height']) || empty($_POST['width']) || empty($_POST['length']))) {
            $error = 'Please provide dimensions for Furniture product.';
        }

        if (!isset($error)) {
            // Проверка на уникальность SKU
            $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
            $stmt->execute([$sku]);
            $skuExists = $stmt->fetchColumn();
            
            if ($skuExists) {
                $error = 'Product with this SKU already exists.';
            } else {
                // Сохранение нового продукта
                if ($productType === 'DVD') {
                    $size = $_POST['size'];
                    $stmt = $db->prepare("INSERT INTO products (sku, name, price, product_type, size_mb) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$sku, $name, $price, $productType, $size]);
                } elseif ($productType === 'Book') {
                    $weight = $_POST['weight'];
                    $stmt = $db->prepare("INSERT INTO products (sku, name, price, product_type, weight_kg) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$sku, $name, $price, $productType, $weight]);
                } elseif ($productType === 'Furniture') {
                    $height = $_POST['height'];
                    $width = $_POST['width'];
                    $length = $_POST['length'];
                    $dimensions = "$height x $width x $length";
                    $stmt = $db->prepare("INSERT INTO products (sku, name, price, product_type, dimensions) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$sku, $name, $price, $productType, $dimensions]);
                }

                // Перенаправление на страницу списка продуктов
                header('Location: index.php');
                exit;
            }
        }
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
</head>
<body>
    <header>
        <h1>Add New Product</h1>
        <button onclick="location.href='index.php'">Back to Product List</button>
    </header>
    <main>
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form id="product_form" method="POST" action="add-product.php">
            <div>
                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" required>
            </div>
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" required step="0.01">
            </div>
            <div>
                <label for="productType">Product Type:</label>
                <select id="productType" name="productType" required onchange="updateProductFields()">
                    <option value="DVD">DVD</option>
                    <option value="Book">Book</option>
                    <option value="Furniture">Furniture</option>
                </select>
            </div>

            <div id="dvd-fields" class="product-specific-fields">
                <label for="size">Size (MB) for DVD:</label>
                <input type="number" id="size" name="size" min="1">
            </div>

            <div id="book-fields" class="product-specific-fields">
                <label for="weight">Weight (Kg) for Book:</label>
                <input type="number" id="weight" name="weight" min="0.1" step="0.1">
            </div>

            <div id="furniture-fields" class="product-specific-fields">
                <label for="height">Height (cm) for Furniture:</label>
                <input type="number" id="height" name="height" min="1">
                <label for="width">Width (cm) for Furniture:</label>
                <input type="number" id="width" name="width" min="1">
                <label for="length">Length (cm) for Furniture:</label>
                <input type="number" id="length" name="length" min="1">
            </div>

            <div>
                <button type="submit">Save</button>
                <button type="button" onclick="window.location.href='index.php'">Cancel</button>
            </div>
        </form>
    </main>

    <script>
        function updateProductFields() {
            const productType = document.getElementById('productType').value;
            
            // Скрываем все поля
            document.querySelectorAll('.product-specific-fields').forEach(function(element) {
                element.style.display = 'none';
            });

            // Показываем соответствующие поля
            if (productType === 'DVD') {
                document.getElementById('dvd-fields').style.display = 'block';
            } else if (productType === 'Book') {
                document.getElementById('book-fields').style.display = 'block';
            } else if (productType === 'Furniture') {
                document.getElementById('furniture-fields').style.display = 'block';
            }
        }

        // Инициализация при загрузке страницы
        window.onload = updateProductFields;
    </script>
</body>
</html>
