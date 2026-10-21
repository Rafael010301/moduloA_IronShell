<?php
/**
 * Funções de Segurança, Autenticação e Sanitização (RNF01)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitiza dados de texto para prevenir ataques de Cross-Site Scripting (XSS)
 *
 * @param string $data
 * @return string
 */
function sanitizeOutput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Verifica se o usuário possui uma sessão ativa. Se não, redireciona para o login.
 */
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?route=login");
        exit();
    }
}

/**
 * Controla o acesso baseado nos perfis permitidos (Administrador, Gestor, Despachante, Motorista)
 *
 * @param array $allowedRoles
 */
function requireRole(array $allowedRoles) {
    requireAuth();
    if (!in_array($_SESSION['user_role'], $allowedRoles)) {
        http_response_code(403);
        die("Acesso Negado: Seu perfil de usuário não possui permissão para acessar esta área.");
    }
}

/**
 * Gera um token CSRF único por sessão para proteger os formulários
 *
 * @return string
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida o token CSRF enviado em requisições POST
 *
 * @param string|null $token
 * @return bool
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}