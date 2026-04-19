<?php
class Database {

    private $host = "mysql.railway.internal";       
    private $db_name = "railway";    
    private $username = "root";   
    private $password = "jQDEEwMxChSGhEMZihAWZrxtYCjewDVQ";   
    private $port = "3306";

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {
            echo json_encode([
                "error" => "Connexion echouee",
                "message" => $exception->getMessage()
            ]);
            exit();
        }

        return $this->conn;
    }
}
?>
