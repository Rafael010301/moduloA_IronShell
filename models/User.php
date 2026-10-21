<?php
/**
 * Modelo de Utilizador (User Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class User {

    /**
     * Procura um utilizador pelo endereço de e-mail
     * 
     * @param string $email
     * @return array|false
     */
    public static function findByEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id_usuario, nome, email, senha_hash, perfil FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Regista um novo utilizador na base de dados
     * 
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param string $perfil (admin, gestor, despachante, motorista)
     * @return bool
     */
    public static function create($nome, $email, $senha, $perfil) {
        $db = Database::getConnection();
        
        // Encriptação segura da palavra-passe com BCRYPT
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
        
        $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES (:nome, :email, :senha_hash, :perfil)");
        
        return $stmt->execute([
            ':nome'       => $nome,
            ':email'      => $email,
            ':senha_hash' => $senhaHash,
            ':perfil'     => $perfil
        ]);
    }
}