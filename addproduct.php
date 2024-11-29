<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'classes/Database.php';

require_once 'classes/ProductFactory.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = (new Database())->connect();

        $sku = $_POST['sku'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $productType = $_POST['productType'];

        $extraData = [];
        if ($productType === 'Book') {
            $extraData['weight'] = $_POST['weight'];
        } elseif ($productType === 'DVD') {
            $extraData['size'] = $_POST['size'];
        } elseif ($productType === 'Furniture') {
            $extraData['height'] = $_POST['height'];
            $extraData['width'] = $_POST['width'];
            $extraData['length'] = $_POST['length'];
        }

        $product = ProductFactory::createProduct($productType, $sku, $name, $price, $extraData);

        $product->save($db);

        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Add</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/script2.js" defer></script>
</head>
<body>
    <header>
        <h1>Product Add</h1>
        <div class="action-buttons">
            <button form="product_form" type="submit" class="save-btn">Save</button>
            <button onclick="location.href='../index.php'" class="cancel-btn">Cancel</button>
        </div>
    </header>
    <main class="form">
        <form id="product_form" method="POST" action="addproduct.php">
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" placeholder="Enter SKU" required>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter product name" required>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" placeholder="Enter price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="productType">Type Switcher</label>
                <select id="productType" name="productType" required>
                    <option value="" disabled selected>Select Type</option>
                    <option value="DVD">DVD</option>
                    <option value="Book">Book</option>
                    <option value="Furniture">Furniture</option>
                </select>
            </div>

            <!-- DVD-specific -->
            <div id="DVD" class="type-specific">
                <label for="size">Size (MB)</label>
                <input type="number" id="size" name="size" placeholder="Enter size in MB">
                <p class="description">"Please provide size in MB."</p>
            </div>

            <!-- Book-specific -->
            <div id="Book" class="type-specific">
                <label for="weight">Weight (KG)</label>
                <input type="number" id="weight" name="weight" placeholder="Enter weight in KG">
                <p class="description">"Please provide weight in KG."</p>
            </div>

            <!-- Furniture-specific -->
            <div id="Furniture" class="type-specific">
                <label for="height">Height (CM)</label>
                <input type="number" id="height" name="height" placeholder="Enter height">
                <label for="width">Width (CM)</label>
                <input type="number" id="width" name="width" placeholder="Enter width">
                <label for="length">Length (CM)</label>
                <input type="number" id="length" name="length" placeholder="Enter length">
                <p class="description">"Please provide dimensions in HxWxL format."</p>
            </div>
        </form>


    </main>
    
    <footer>
            <p>Scandiweb Test assignment</p>
        </footer>
</body>
</html>
