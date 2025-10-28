<?php

require_once __DIR__ . '/../models/Hotel.php';

class AuthController
{
    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $u = Hotel::buscarUsuario($usuario);

            if ($u && $u['senha'] === $senha) {
                $_SESSION['usuario_id']   = $u['id'];
                $_SESSION['usuario_nome'] = $u['nome'];
                header("Location: index.php?r=hotel/index");
                exit;
            }
            $error = "Usuário ou senha inválidos.";
            require __DIR__ . '/../views/auth_login.php';
            return;
        }


        require __DIR__ . '/../views/auth_login.php';
    }

    public static function logout()
    {
        session_destroy();
        header("Location: index.php?r=auth/login");
        exit;
    }
}
