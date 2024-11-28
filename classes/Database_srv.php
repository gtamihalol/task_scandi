<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'multatulin_bananoviy';
    private $username = 'multatulin_bananoviy';
    private $password = 'mq02al3ak9';
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
