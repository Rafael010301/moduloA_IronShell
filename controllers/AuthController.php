<?php
// controllers/AuthController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/security.php';

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                die("Falha na validação CSRF.");
            }

            if (!$email || empty($password)) {
                return "Por favor, preencha todos os campos corretamente.";
            }

            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id_usuario, nome, email, senha_hash, perfil FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // Verificação segura do password_verify
            if ($user && password_verify($password, $user['senha_hash'])) {
                session_regenerate_id(true);

                $_SESSION['user_id']   = $user['id_usuario'];
                $_SESSION['user_name'] = $user['nome'];
                $_SESSION['user_role'] = $user['perfil'];

                header("Location: index.php?route=dashboard");
                exit();
            } else {
                return "Credenciais inválidas. Verifique seu e-mail e senha.";
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php?route=login");
        exit();
    }
}