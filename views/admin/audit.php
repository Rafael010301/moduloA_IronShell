<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../config/database.php';

$db = Database::getConnection();

// Consulta segura de logs/alertas de auditoria
$logs = $db->query("SELECT * FROM alertas ORDER BY id_alerta DESC")->fetchAll();

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid pb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Auditoria e Registo de Atividades (RF12)</h2>
            <p class="text-muted">Painel de conformidade e rastreabilidade de eventos executados na plataforma.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Histórico de Eventos e Auditoria</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Registo</th>
                                <th>Tipo de Registo</th>
                                <th>Descrição / Atividade</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Nenhum registo de auditoria encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $l): ?>
                                    <tr>
                                        <td><strong>#<?= $l['id_alerta']; ?></strong></td>
                                        <td><span class="badge bg-secondary"><?= sanitizeOutput($l['tipo_alerta'] ?? 'AUDITORIA'); ?></span></td>
                                        <td><?= sanitizeOutput($l['mensagem'] ?? ''); ?></td>
                                        <td><span class="badge bg-success">Registado</span></td>
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