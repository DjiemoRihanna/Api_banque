<?php
class CompteController {

    private $compte;

    public function __construct($compteModel) {
        $this->compte = $compteModel;
    }

    // CREATE ACCOUNT
    public function create($data) {
        $this->compte->nom_titulaire = $data->nom;
        $this->compte->type_compte = $data->type;
        $this->compte->solde = $data->solde;

        if ($this->compte->create()) {
            return ["message" => "Compte créé avec succès"];
        }

        return ["error" => "Erreur création compte"];
    }

    // GET ALL
    public function getAll() {
        $stmt = $this->compte->getAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // GET ONE
    public function getOne($id) {
        $this->compte->id = $id;
        return $this->compte->getOne();
    }

    // DEPOT
    public function depot($id, $montant) {
        $this->compte->id = $id;
        $data = $this->compte->getOne();

        if (!$data) return ["error" => "Compte introuvable"];

        $this->compte->solde = $data['solde'] + $montant;

        $this->compte->updateSolde();

        return ["message" => "Dépôt effectué", "solde" => $this->compte->solde];
    }

    // RETRAIT
    public function retrait($id, $montant) {
        $this->compte->id = $id;
        $data = $this->compte->getOne();

        if (!$data) return ["error" => "Compte introuvable"];

        if ($data['solde'] < $montant) {
            return ["error" => "Solde insuffisant"];
        }

        $this->compte->solde = $data['solde'] - $montant;

        $this->compte->updateSolde();

        return ["message" => "Retrait effectué", "solde" => $this->compte->solde];
    }
}
?>