<?php
require_once 'AbstractProduct.php';


class Book extends AbstractProduct {
    private $weight;

    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight;
    }

    public function save($db) {
        $stmt = $db->prepare("INSERT INTO products (sku, name, price, product_type, weight_kg) VALUES (?, ?, ?, 'Book', ?)");
        $stmt->execute([$this->sku, $this->name, $this->price, $this->weight]);
    }

    public function display() {
        return "{$this->name} ({$this->sku}): {$this->price}$, Weight: {$this->weight} KG";
    }
}
