<?php 
require_once __DIR__ . '/../../includes/security.php';
requireAuth(); // Exige autenticação para visualizar a página
require_once __DIR__ . '/../layouts/header.php'; 
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php?route=dashboard">SENN AI-X</a>
        <div class="d-flex align-items-center text-white">
            <span class="me-3">Olá, <strong><?= sanitizeOutput($_SESSION['user_name'] ?? 'Usuário'); ?></strong> (<?= strtoupper(sanitizeOutput($_SESSION['user_role'] ?? '')); ?>)</span>
            <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4 shadow-sm">
                <h4 class="mb-3">Painel de Controle Operacional</h4>
                <p class="text-muted">Bem-vindo à plataforma de gestão logística. Escolha uma das opções abaixo para começar:</p>
                <hr>
                <div class="row g-3">
                    <!-- Card de Frotas -->
                    <div class="col-md-3">
                        <a href="index.php?route=fleet" class="text-decoration-none">
                            <div class="card bg-primary text-white p-3 text-center shadow-sm h-100">
                                <h5>Frotas</h5>
                                <p class="mb-0 small">Cadastrar e gerenciar veículos</p>
                            </div>
                        </a>
                    </div>

                    <!-- Card de Pedidos -->
                    <div class="col-md-3">
                        <a href="index.php?route=orders" class="text-decoration-none">
                            <div class="card bg-success text-white p-3 text-center shadow-sm h-100">
                                <h5>Pedidos</h5>
                                <p class="mb-0 small">Importar e acompanhar entregas</p>
                            </div>
                        </a>
                    </div>

                    <!-- Card de Rastreamento -->
                    <div class="col-md-3">
                        <a href="index.php?route=tracking" class="text-decoration-none">
                            <div class="card bg-warning text-dark p-3 text-center shadow-sm h-100">
                                <h5>Rastreamento</h5>
                                <p class="mb-0 small">Mapa em tempo real</p>
                            </div>
                        </a>
                    </div>

                    <!-- Card de IA & Predict -->
                    <div class="col-md-3">
                        <a href="index.php?route=predict" class="text-decoration-none">
                            <div class="card bg-info text-white p-3 text-center shadow-sm h-100">
                                <h5>IA & Predict</h5>
                                <p class="mb-0 small">Previsões e otimizações</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>