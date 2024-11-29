<?php
require_once 'Book.php';
require_once 'DVD.php';
require_once 'Furniture.php';

class ProductFactory {
    public static function createProduct($productType, $sku, $name, $price, $extraData) {
        switch ($productType) {
            case 'Book':
                return new Book($sku, $name, $price, $extraData['weight']);
            case 'DVD':
                return new DVD($sku, $name, $price, $extraData['size']);
            case 'Furniture':
                return new Furniture($sku, $name, $price, $extraData['height'], $extraData['width'], $extraData['length']);
            default:
                throw new Exception("Invalid product type");
        }
    }
}
?>
