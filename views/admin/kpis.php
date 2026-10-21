<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/Kpi.php';

$metrics = Kpi::getMetrics();

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
            <h2 class="fw-bold">Painel de Indicadores Gerenciais (RF09)</h2>
            <p class="text-muted">Métricas consolidadas de desempenho operacional, eficiência da frota e nível de serviço.</p>
        </div>
    </div>

    <!-- Cartões de KPIs principais -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <span class="text-muted small fw-semibold">Total de Pedidos</span>
                <h3 class="fw-bold my-1 text-primary"><?= $metrics['total_pedidos']; ?></h3>
                <span class="small text-muted">Volume processado</span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <span class="text-muted small fw-semibold">Entregues com Sucesso</span>
                <h3 class="fw-bold my-1 text-success"><?= $metrics['entregues']; ?></h3>
                <span class="small text-muted">Concluídos na ponta</span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <span class="text-muted small fw-semibold">Ocorrências / Falhas</span>
                <h3 class="fw-bold my-1 text-danger"><?= $metrics['falhas']; ?></h3>
                <span class="small text-muted">Requer atenção</span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <span class="text-muted small fw-semibold">Média Risco Atraso (IA)</span>
                <h3 class="fw-bold my-1 text-warning"><?= $metrics['media_risco']; ?>%</h3>
                <span class="small text-muted">Índice preditivo</span>
            </div>
        </div>
    </div>

    <!-- Tabela Resumo da Frota -->
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="card-title fw-bold mb-3">Recursos Operacionais</h5>
                <div class="row text-center">
                    <div class="col-md-6 border-end">
                        <span class="text-muted small">Veículos Registados</span>
                        <h4 class="fw-bold text-dark mt-1"><?= $metrics['total_veiculos']; ?></h4>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Motoristas Ativos</span>
                        <h4 class="fw-bold text-dark mt-1"><?= $metrics['total_motoristas']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>