<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);

require_once __DIR__ . '/../layouts/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?route=dashboard">SENN AI-X</a>
        <div class="d-flex align-items-center text-white">
            <a href="index.php?route=dashboard" class="btn btn-outline-light btn-sm me-3">Voltar ao Dashboard</a>
            <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Relatórios e Exportação de Dados (RF10)</h2>
            <p class="text-muted">Central de extração de relatórios operacionais, dados de entregas e auditoria de frota em formato CSV.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Relatório Geral de Pedidos e Entregas -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0 h-100">
                <h5 class="card-title fw-bold text-primary mb-3">Relatório de Pedidos e Entregas</h5>
                <p class="card-text text-muted small">Exporta o histórico completo de pedidos, incluindo status de entrega, risco de atraso calculado por IA, moradas de destino e motoristas associados.</p>
                <div class="mt-auto pt-3">
                    <a href="index.php?route=export_csv" class="btn btn-primary w-100 fw-bold">Descarregar Relatório CSV</a>
                </div>
            </div>
        </div>

        <!-- Auditoria de Sistema -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0 h-100">
                <h5 class="card-title fw-bold text-secondary mb-3">Auditoria e Conformidade</h5>
                <p class="card-text text-muted small">Informações consolidadas para conformidade com os padrões de segurança e registo de logs operacionais da plataforma.</p>
                <div class="mt-auto pt-3">
                    <button class="btn btn-outline-secondary w-100 fw-bold" disabled>Exportação Avançada (Breve)</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>