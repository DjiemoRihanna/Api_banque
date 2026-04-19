<?php
class Compte {
    private $conn;
    private $table = "comptes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM comptes ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM comptes WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO comptes (nom_titulaire, type_compte, solde)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([
            $data['nom'],
            $data['type'],
            $data['solde']
        ]);
    }

    public function updateSolde($id, $solde) {
        $stmt = $this->conn->prepare("
            UPDATE comptes SET solde=? WHERE id=?
        ");
        return $stmt->execute([$solde, $id]);
    }
}