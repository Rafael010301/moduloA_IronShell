<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../config/database.php';

$db = Database::getConnection();
$mensagemSucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idVeiculo = intval($_POST['id_veiculo'] ?? 0);
    $novaData = trim($_POST['proxima_manutencao'] ?? '');

    if ($idVeiculo > 0 && !empty($novaData)) {
        $stmt = $db->prepare("UPDATE veiculos SET proxima_manutencao = :data WHERE id_veiculo = :id");
        $stmt->execute([':data' => $novaData, ':id' => $idVeiculo]);
        $mensagemSucesso = "Data de manutenção atualizada com sucesso.";
    }
}

$veiculos = $db->query("SELECT * FROM veiculos ORDER BY id_veiculo DESC")->fetchAll();

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid pb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Gestão de Manutenção Preventiva (RF13)</h2>
            <p class="text-muted">Controlo de prazos de revisão mecânica e inspeção técnica da frota.</p>
        </div>
    </div>

    <?php if (!empty($mensagemSucesso)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= sanitizeOutput($mensagemSucesso); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Calendário de Manutenção da Frota</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Placa</th>
                                <th>Tipo</th>
                                <th>Próxima Manutenção</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($veiculos)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Nenhum veículo registado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($veiculos as $v): ?>
                                    <tr>
                                        <td><strong>#<?= $v['id_veiculo']; ?></strong></td>
                                        <td><?= sanitizeOutput($v['placa']); ?></td>
                                        <td><?= sanitizeOutput($v['tipo'] ?? 'Utilitario'); ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= sanitizeOutput($v['proxima_manutencao'] ?? 'Não agendada'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form action="index.php?route=maintenance" method="POST" class="row g-2">
                                                <input type="hidden" name="id_veiculo" value="<?= $v['id_veiculo']; ?>">
                                                <div class="col-auto">
                                                    <input type="date" name="proxima_manutencao" class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-outline-primary btn-sm fw-bold">Agendar</button>
                                                </div>
                                            </form>
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