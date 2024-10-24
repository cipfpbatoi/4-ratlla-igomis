<?php

namespace Joc4enRatlla\Controllers;

class AuthController
{
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function login($nom_usuari, $contrasenya) {
        $user = $this->userModel->findUserByUsername($nom_usuari);

        if (!$user) {
            if ($this->userModel->createUser($nom_usuari, $contrasenya)) {
                $user = $this->userModel->findUserByUsername($nom_usuari);
            }
        }

        if ($user && password_verify($contrasenya, $user['contrasenya'])) {
            // Contrasenya correcta, guardar sessió o qualsevol altra acció
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom_usuari'] = $user['nom_usuari'];
            return true;
        }

        // Si les credencials són incorrectes
        return false;
    }
}