<?php
require_once 'AbstractProduct.php';

class Furniture extends AbstractProduct {
    private $height;
    private $width;
    private $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        parent::__construct($sku, $name, $price);
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function save($db) {
        $stmt = $db->prepare("INSERT INTO products (sku, name, price, product_type, dimensions) VALUES (?, ?, ?, 'Furniture', ?)");
        $dimensions = "{$this->height}x{$this->width}x{$this->length}";
        $stmt->execute([$this->sku, $this->name, $this->price, $dimensions]);
    }

    public function display() {
        return "{$this->name} ({$this->sku}): {$this->price}$, Dimensions: {$this->height}x{$this->width}x{$this->length} CM";
    }
}
?>
