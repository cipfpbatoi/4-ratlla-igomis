<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Services\Connect;
use \PDO;
class User
{
    private $db;

    public function __construct( ) {
        $this->db = Connect::restore()->getConnection();
    }

    public function findUserByUsername($nom_usuari) {
        $query = "SELECT * FROM usuaris WHERE nom_usuari = :nom_usuari";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom_usuari', $nom_usuari);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insereix un nou usuari a la base de dades
    public function createUser($nom_usuari, $contrasenya) {
        $query = "INSERT INTO usuaris (nom_usuari, contrasenya) VALUES (:nom_usuari, :contrasenya)";
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($contrasenya, PASSWORD_DEFAULT); // Hash de la contrasenya per seguretat
        $stmt->bindParam(':nom_usuari', $nom_usuari);
        $stmt->bindParam(':contrasenya', $hashedPassword);
        return $stmt->execute(); // Retorna true si la inserció ha tingut èxit
    }
}