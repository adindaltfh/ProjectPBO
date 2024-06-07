<?php
namespace App\Model;

use PDO;
use PDOException;

class Model
{
    protected $db;

    public function __construct() {
        require 'config.php';
        try {
            $conn = new PDO("mysql:host=$servername;dbname={$database}", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db = $conn;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
            
    }

    public function findById($table, $id_column, $id)
    {
        $sql = "SELECT * FROM $table WHERE $id_column = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}