<?php
/**
 * Front Controller Principal (Roteador da Aplicação)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

// Carrega os arquivos essenciais de segurança e controladores
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/FleetController.php';

// Captura a rota solicitada via URL (padrão: login)
$route = $_GET['route'] ?? 'login';

// Roteamento centralizado do sistema
switch ($route) {
    case 'login':
        $auth = new AuthController();
        $error = $auth->login(); // Executa a lógica de autenticação se for POST
        require __DIR__ . '/views/auth/login.php';
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;

    case 'dashboard':
        requireAuth(); // Protege a rota exigindo login ativo
        require __DIR__ . '/views/dashboard/index.php';
        break;

    case 'fleet_create':
        requireAuth();
        $fleet = new FleetController();
        $fleet->createVehicle();
        break;
    case 'fleet':
        requireAuth();
        require __DIR__ . '/views/admin/fleet.php';
        break;
    case 'drivers':
        requireAuth();
        require __DIR__ . '/views/admin/drivers.php';
        break;
    case 'orders':
        requireAuth();
        require __DIR__ . '/views/admin/orders.php';
        break;
    case 'optimizer':
        requireAuth();
        require __DIR__ . '/views/admin/optimizer.php';
        break;

    default:
        http_response_code(404);
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
                <h2>404 - Página não encontrada</h2>
                <p>A rota solicitada não existe no sistema.</p>
                <a href='index.php?route=login'>Voltar para o Login</a>
              </div>";
        break;
}