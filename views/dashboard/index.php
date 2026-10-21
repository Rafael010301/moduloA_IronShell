<?php
require_once __DIR__ . '/../../includes/security.php';
requireAuth();

// Carregar modelos para estatísticas em tempo real
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Vehicle.php';
require_once __DIR__ . '/../../models/Driver.php';

$totalPedidos = count(Order::all());
$totalVeiculos = count(Vehicle::all());
$totalMotoristas = count(Driver::all());

require_once __DIR__ . '/../layouts/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?route=dashboard">
            <i class="bi bi-truck-front-fill me-2"></i>SENN AI-X <span class="badge bg-primary fs-6">Logística</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-3 text-light small">
                    Olá, <strong><?= sanitizeOutput($_SESSION['user_nome'] ?? 'Utilizador'); ?></strong> 
                    <span class="badge bg-secondary ms-1"><?= sanitizeOutput($_SESSION['user_cargo'] ?? 'operador'); ?></span>
                </li>
                <li class="nav-item">
                    <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <!-- Cabeçalho de Boas-Vindas -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">Painel de Controlo Operacional</h2>
            <p class="text-muted">Bem-vindo à plataforma inteligente de gestão de frotas e otimização de rotas.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="text-muted small"><i class="bi bi-calendar-event me-1"></i><?= date('d/m/Y'); ?></span>
        </div>
    </div>

    <!-- Indicadores Rápidos (Cards de Estatísticas) -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-semibold mb-1">Total de Pedidos</h6>
                        <h3 class="fw-bold mb-0"><?= $totalPedidos; ?></h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-box-seam"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-semibold mb-1">Veículos na Frota</h6>
                        <h3 class="fw-bold mb-0"><?= $totalVeiculos; ?></h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-truck"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning text-dark p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-semibold mb-1">Motoristas Registados</h6>
                        <h3 class="fw-bold mb-0"><?= $totalMotoristas; ?></h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-person-badge"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secção de Navegação e Acesso aos Módulos -->
    <h4 class="fw-bold mb-3 text-secondary">Módulos do Sistema</h4>
    <div class="row g-4">
        
        <!-- Módulo de Pedidos e CSV (RF04) -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="mb-3 text-primary fs-1"><i class="bi bi-file-earmark-spreadsheet"></i></div>
                    <h5 class="card-title fw-bold">Gestão de Pedidos</h5>
                    <p class="card-text text-muted small flex-grow-1">Importação em lote via CSV, controlo de status e gestão de entregas.</p>
                    <a href="index.php?route=orders" class="btn btn-outline-primary btn-sm mt-3 w-100">Aceder a Pedidos</a>
                </div>
            </div>
        </div>

        <!-- Módulo de Frotas (RF02) -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="mb-3 text-success fs-1"><i class="bi bi-truck"></i></div>
                    <h5 class="card-title fw-bold">Gestão de Frotas</h5>
                    <p class="card-text text-muted small flex-grow-1">Registo de veículos, capacidades de carga volumétrica e placas.</p>
                    <a href="index.php?route=fleet" class="btn btn-outline-success btn-sm mt-3 w-100">Aceder à Frota</a>
                </div>
            </div>
        </div>

        <!-- Módulo de Motoristas (RF03) -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="mb-3 text-warning fs-1"><i class="bi bi-person-badge"></i></div>
                    <h5 class="card-title fw-bold">Gestão de Motoristas</h5>
                    <p class="card-text text-muted small flex-grow-1">Registo de condutores, categorias de CNH e atribuição de veículos.</p>
                    <a href="index.php?route=drivers" class="btn btn-outline-warning btn-sm mt-3 w-100 text-dark">Aceder a Motoristas</a>
                </div>
            </div>
        </div>

        <!-- Módulo de Otimização IA (RF05) -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="mb-3 text-danger fs-1"><i class="bi bi-cpu"></i></div>
                    <h5 class="card-title fw-bold">Otimização de Rotas</h5>
                    <p class="card-text text-muted small flex-grow-1">Cruzamento inteligente por IA Predict e alocação de rotas de entrega.</p>
                    <a href="index.php?route=optimizer" class="btn btn-outline-danger btn-sm mt-3 w-100">Aceder a Rotas IA</a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    }
</style>

</body>
</html>