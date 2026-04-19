<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"));

require_once "../config/database.php";
require_once "../models/Compte.php";

$db = (new Database())->connect();
$compte = new Compte($db);

$method = $_SERVER['REQUEST_METHOD'];

// 🔹 GET → LISTE ou UN COMPTE
if ($method == "GET") {

    if (isset($_GET['id'])) {
        $result = $compte->getOne($_GET['id']);
    } else {
        $result = $compte->getAll();
    }

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// 🔹 POST → CREATION / DEPOT / RETRAIT
if ($method == "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    // CREATE
    if (isset($data['nom'])) {
        $compte->create($data);
        echo json_encode(["message" => "Compte créé"]);
        exit;
    }

    // DEPOT / RETRAIT
    if (isset($data['action'])) {

        $c = $compte->getOne($data['id']);

        if (!$c) {
            echo json_encode(["error" => "Compte introuvable"]);
            exit;
        }

        $solde = $c['solde'];

        if ($data['action'] == "depot") {
            $solde += $data['montant'];
        }

        if ($data['action'] == "retrait") {
            if ($solde < $data['montant']) {
                echo json_encode(["error" => "Solde insuffisant"]);
                exit;
            }
            $solde -= $data['montant'];
        }

        $compte->updateSolde($data['id'], $solde);

        echo json_encode([
            "message" => "Transaction réussie",
            "nouveau_solde" => $solde
        ]);
        exit;
    }
}