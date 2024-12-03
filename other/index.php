<?php
// Подключение к базе данных
require_once "Database.php";

// Создание объекта Database и получение соединения
$database = new Database();
$conn = $database->getConnection();

// Запрос для получения всех продуктов
$query = "SELECT * FROM products";
$stmt = $conn->prepare($query);
$stmt->execute();

// Извлечение всех записей
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Проверка наличия продуктов
if (count($products) > 0) {
    // Вывод каждого продукта
    foreach ($products as $product) {
        echo "<div class='product'>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($product['name']) . "</p>";
        echo "<p><strong>SKU:</strong> " . htmlspecialchars($product['sku']) . "</p>";
        echo "<p><strong>Price:</strong> $" . htmlspecialchars($product['price']) . "</p>";
        echo "<p><strong>Type:</strong> " . htmlspecialchars($product['type']) . "</p>";

        // В зависимости от типа товара отображать дополнительные данные
        if ($product['type'] == 'DVD') {
            echo "<p><strong>Size (MB):</strong> " . htmlspecialchars($product['size_mb']) . " MB</p>";
        } elseif ($product['type'] == 'Book') {
            echo "<p><strong>Weight (KG):</strong> " . htmlspecialchars($product['weight_kg']) . " KG</p>";
        } elseif ($product['type'] == 'Furniture') {
            echo "<p><strong>Dimensions (HxWxL):</strong> " . htmlspecialchars($product['dimensions']) . "</p>";
        }

        echo "</div><hr>";
    }
} else {
    echo "<p>No products found.</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="styles.css"> <!-- Укажите путь к CSS -->
</head>
<body>
    <header>
        <h1>Product List</h1>
        <div>
            <a href="/other/add_product.php" class="btn">ADD</a>
            <button id="delete-product-btn" class="btn" form="product-list" type="submit">MASS DELETE</button>
        </div>
    </header>
    <main>
        <form method="POST" action="delete_products.php" id="product-list">
            <div class="product-container">
                <?php
                // Подключение и извлечение продуктов
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $query = "SELECT * FROM products ORDER BY id ASC";
                    $stmt = $conn->query($query);

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="product-item">';
                            echo '<input type="checkbox" class="delete-checkbox" name="delete[]" value="' . htmlspecialchars($row['id']) . '">';
                            echo '<p>SKU: ' . htmlspecialchars($row['sku']) . '</p>';
                            echo '<p>Name: ' . htmlspecialchars($row['name']) . '</p>';
                            echo '<p>Price: $' . htmlspecialchars($row['price']) . '</p>';

                            switch ($row['type']) {
                                case 'DVD':
                                    echo '<p>Size: ' . htmlspecialchars($row['size_mb']) . ' MB</p>';
                                    break;
                                case 'Book':
                                    echo '<p>Weight: ' . htmlspecialchars($row['weight_kg']) . ' Kg</p>';
                                    break;
                                case 'Furniture':
                                    echo '<p>Dimensions: ' . htmlspecialchars($row['dimensions']) . '</p>';
                                    break;
                            }
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No products found.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<p>Error: ' . $e->getMessage() . '</p>';
                }
                ?>
            </div>
        </form>
    </main>
    <footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
    <script>
        // Проверка перед удалением
        document.getElementById('delete-product-btn').addEventListener('click', function (event) {
            const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
            if (checkboxes.length === 0) {
                alert("Please select at least one product to delete.");
                event.preventDefault();
            } else if (!confirm("Are you sure you want to delete selected products?")) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
