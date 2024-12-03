<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'errors.log');

require_once 'classes/Database.php';
require_once 'classes/ProductFactory.php';

function validateInput($productType, $sku, $name, $price, $extraData) {
    $errors = [];

    if (empty($sku)) {
        $errors[] = "SKU is required.";
    }
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    if ($productType === 'Book') {
        if (empty($extraData['weight']) || !is_numeric($extraData['weight']) || $extraData['weight'] <= 0) {
            $errors[] = "Weight must be a positive number.";
        }
    } elseif ($productType === 'DVD') {
        if (empty($extraData['size']) || !is_numeric($extraData['size']) || $extraData['size'] <= 0) {
            $errors[] = "Size must be a positive number.";
        }
    } elseif ($productType === 'Furniture') {
        if (empty($extraData['height']) || !is_numeric($extraData['height']) || $extraData['height'] <= 0) {
            $errors[] = "Height must be a positive number.";
        }
        if (empty($extraData['width']) || !is_numeric($extraData['width']) || $extraData['width'] <= 0) {
            $errors[] = "Width must be a positive number.";
        }
        if (empty($extraData['length']) || !is_numeric($extraData['length']) || $extraData['length'] <= 0) {
            $errors[] = "Length must be a positive number.";
        }
    } else {
        $errors[] = "Invalid product type selected.";
    }

    return $errors;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = (new Database())->connect();

        $sku = trim($_POST['sku'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $productType = $_POST['productType'] ?? '';

        $extraData = [];
        if ($productType === 'Book') {
            $extraData['weight'] = trim($_POST['weight'] ?? '');
        } elseif ($productType === 'DVD') {
            $extraData['size'] = trim($_POST['size'] ?? '');
        } elseif ($productType === 'Furniture') {
            $extraData['height'] = trim($_POST['height'] ?? '');
            $extraData['width'] = trim($_POST['width'] ?? '');
            $extraData['length'] = trim($_POST['length'] ?? '');
        }

        $errors = validateInput($productType, $sku, $name, $price, $extraData);

        if (!empty($errors)) {
            foreach ($errors as $error) {

            }
        } else {
            $product = ProductFactory::createProduct($productType, $sku, $name, $price, $extraData);

            $product->save($db);

            header('Location: index.php');
            exit();
        }
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
        if (strpos($errorMessage, '1062 Duplicate entry') !== false) {
            $pattern = "/Duplicate entry '(.+?)' for key '(.+?)'/";
            if (preg_match($pattern, $errorMessage, $matches)) {
                $duplicateValue = $matches[1];
                $duplicateKey = $matches[2];
                $errors[] = "The item with this SKU ('$duplicateValue') already exists. Please choose a different SKU.";
            } else {
                $errors[] = "db error lol";
            }
        } else {
            $errors[] = "An unexpected error occurred: " . htmlspecialchars($e->getMessage());
        }
    } catch (Exception $e) {
        $errors[] = "Error: " . htmlspecialchars($e->getMessage());
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
    <script>
        function handleTypeSwitcherChange() {
            const typeSwitcher = document.getElementById("productType");
            const selectedType = typeSwitcher.value;

            // Hide all type-specific fields
            document.querySelectorAll(".type-specific").forEach(el => {
                el.style.display = "none";
            });

            // Show the fields for the selected type
            if (selectedType) {
                const typeFields = document.getElementById(selectedType);
                if (typeFields) {
                    typeFields.style.display = "block";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            handleTypeSwitcherChange(); // Trigger on page load
            document.getElementById("productType").addEventListener("change", handleTypeSwitcherChange);
        });
    </script>
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
        <!-- Error container -->
        <?php if (!empty($errors)): ?>
            <div class="error-container">
                <strong>The following errors occurred:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="product_form" method="POST" action="addproduct.php">
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" placeholder="Enter SKU" required value="<?php echo htmlspecialchars($_POST['sku'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter product name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" placeholder="Enter price" step="0.01" required value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="productType">Type Switcher</label>
                <select id="productType" name="productType" required>
                    <option value="" disabled <?php echo empty($_POST['productType']) ? 'selected' : ''; ?>>Select Type</option>
                    <option value="DVD" <?php echo ($_POST['productType'] ?? '') === 'DVD' ? 'selected' : ''; ?>>DVD</option>
                    <option value="Book" <?php echo ($_POST['productType'] ?? '') === 'Book' ? 'selected' : ''; ?>>Book</option>
                    <option value="Furniture" <?php echo ($_POST['productType'] ?? '') === 'Furniture' ? 'selected' : ''; ?>>Furniture</option>
                </select>
            </div>

            <!-- DVD-specific -->
            <div id="DVD" class="type-specific">
                <label for="size">Size (MB)</label>
                <input type="number" id="size" name="size" placeholder="Enter size in MB" value="<?php echo htmlspecialchars($_POST['size'] ?? ''); ?>">
                <p class="description">"Please provide size in MB."</p>
            </div>

            <!-- Book-specific -->
            <div id="Book" class="type-specific">
                <label for="weight">Weight (KG)</label>
                <input type="number" id="weight" name="weight" placeholder="Enter weight in KG" value="<?php echo htmlspecialchars($_POST['weight'] ?? ''); ?>">
                <p class="description">"Please provide weight in KG."</p>
            </div>

            <!-- Furniture-specific -->
            <div id="Furniture" class="type-specific">
                <label for="height">Height (CM)</label>
                <input type="number" id="height" name="height" placeholder="Enter height" value="<?php echo htmlspecialchars($_POST['height'] ?? ''); ?>">
                <label for="width">Width (CM)</label>
                <input type="number" id="width" name="width" placeholder="Enter width" value="<?php echo htmlspecialchars($_POST['width'] ?? ''); ?>">
                <label for="length">Length (CM)</label>
                <input type="number" id="length" name="length" placeholder="Enter length" value="<?php echo htmlspecialchars($_POST['length'] ?? ''); ?>">
                <p class="description">"Please provide dimensions in HxWxL format."</p>
            </div>
        </form>
    </main>

    <footer>
        <p>Scandiweb Test assignment</p>
    </footer>
</body>
</html>

