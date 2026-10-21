<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/Tracking.php';

$entregas = Tracking::getActiveDeliveries();

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
            <h2>Rastreamento em Tempo Real (RF06)</h2>
            <p class="text-muted">Acompanhamento das entregas, status operacional da frota e localização estimada.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Painel de Monitorização</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Rastreio</th>
                                <th>Cliente / Destino</th>
                                <th>Veículo / Placa</th>
                                <th>Motorista</th>
                                <th>Risco Atraso</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($entregas)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Nenhuma entrega registrada.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($entregas as $e): ?>
                                    <tr>
                                        <td><strong><?= sanitizeOutput($e['codigo_rastreio']); ?></strong></td>
                                        <td><?= sanitizeOutput($e['cliente_nome']); ?><br><small class="text-muted"><?= sanitizeOutput($e['endereco_destino']); ?></small></td>
                                        <td><?= !empty($e['placa']) ? sanitizeOutput($e['placa']) : '<span class="text-muted">Não alocado</span>'; ?></td>
                                        <td><?= !empty($e['motorista_nome']) ? sanitizeOutput($e['motorista_nome']) : '<span class="text-muted">Não atribuído</span>'; ?></td>
                                        <td>
                                            <span class="badge <?= ($e['risco_atraso_percentual'] > 15) ? 'bg-danger' : 'bg-success'; ?>">
                                                <?= number_format($e['risco_atraso_percentual'], 1); ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $badgeClass = match($e['status_entrega']) {
                                                    'entregue' => 'bg-success',
                                                    'em_rota'  => 'bg-primary',
                                                    'falha'    => 'bg-danger',
                                                    default    => 'bg-warning text-dark',
                                                };
                                            ?>
                                            <span class="badge <?= $badgeClass; ?>"><?= strtoupper(sanitizeOutput($e['status_entrega'])); ?></span>
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