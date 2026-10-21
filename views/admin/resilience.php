<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/Resilience.php';

$resilienceStatus = Resilience::checkSystemResilience();

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
            <h2>Modo de Resiliência e Fallback (RF11)</h2>
            <p class="text-muted">Monitorização de redundância de sistemas, integridade operacional e ativação de rotas de contingência.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="card-title fw-bold mb-3">Estado Atual da Infraestrutura</h5>
                
                <div class="alert <?= $resilienceStatus['modo_fallback'] ? 'alert-warning' : 'alert-success'; ?>" role="alert">
                    <h4 class="alert-heading fw-bold">Estado: <?= sanitizeOutput($resilienceStatus['status']); ?></h4>
                    <p class="mb-0"><?= sanitizeOutput($resilienceStatus['mensagem']); ?></p>
                </div>

                <hr>

                <h6 class="fw-bold mt-4 mb-2">Parâmetros de Contingência Ativos</h6>
                <ul class="text-muted small">
                    <li>Redundância de base de dados ativada por conexões PDO persistentes.</li>
                    <li>Substituição automática do motor preditivo por regras heurísticas lineares em caso de latência.</li>
                    <li>Registo automático de incidentes na tabela de auditoria e alertas.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>