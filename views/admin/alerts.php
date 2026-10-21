<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/Alert.php';

$alertas = Alert::all();

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
            <h2>Alertas e Notificações de Anomalias (RF08)</h2>
            <p class="text-muted">Painel de monitorização preditiva de riscos, desvios operacionais e alertas automáticos da frota.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Registo de Ocorrências Ativas</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Código de Rastreio</th>
                                <th>Tipo de Alerta</th>
                                <th>Mensagem / Descrição</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($alertas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Nenhum alerta registado no sistema.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alertas as $a): ?>
                                    <tr>
                                        <td><strong>#<?= $a['id_alerta']; ?></strong></td>
                                        <td><?= !empty($a['codigo_rastreio']) ? sanitizeOutput($a['codigo_rastreio']) : '<span class="text-muted">Geral</span>'; ?></td>
                                        <td><span class="badge bg-warning text-dark"><?= sanitizeOutput($a['tipo_alerta'] ?? 'Aviso'); ?></span></td>
                                        <td><?= sanitizeOutput($a['mensagem']); ?></td>
                                        <td>
                                            <span class="badge bg-secondary">Ativo</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>