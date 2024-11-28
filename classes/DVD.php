<?php
require_once 'AbstractProduct.php';

class DVD extends AbstractProduct {
    private $size;

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->size = $size;
    }

    public function save($db) {
        $stmt = $db->prepare("INSERT INTO products (sku, name, price, product_type, size_mb) VALUES (?, ?, ?, 'DVD', ?)");
        $stmt->execute([$this->sku, $this->name, $this->price, $this->size]);
    }

    public function display() {
        return "{$this->name} ({$this->sku}): {$this->price}$, Size: {$this->size} MB";
    }
}
