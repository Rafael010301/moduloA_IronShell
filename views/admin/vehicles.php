<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../config/database.php';

$db = Database::getConnection();
$mensagemSucesso = '';
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = trim($_POST['placa'] ?? '');
    $tipo = trim($_POST['tipo'] ?? 'Utilitario');
    $capacidadeKg = floatval($_POST['capacidade_kg'] ?? 0);
    $capacidadeVolume = floatval($_POST['capacidade_volume'] ?? 0);

    if (!empty($placa) && $capacidadeKg > 0) {
        $stmt = $db->prepare("INSERT INTO veiculos (placa, tipo, capacidade_kg, capacidade_volume, status_veiculo) VALUES (:placa, :tipo, :kg, :volume, 'disponivel')");
        try {
            $stmt->execute([
                ':placa'  => $placa,
                ':tipo'   => $tipo,
                ':kg'     => $capacidadeKg,
                ':volume' => $capacidadeVolume
            ]);
            $mensagemSucesso = "Veículo registado com sucesso.";
        } catch (PDOException $e) {
            $mensagemErro = "Erro ao registar veículo (placa duplicada ou erro na base de dados).";
        }
    } else {
        $mensagemErro = "Preencha a placa e a capacidade em kg corretamente.";
    }
}

$veiculos = $db->query("SELECT * FROM veiculos ORDER BY id_veiculo DESC")->fetchAll();

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
            <h2>Gestão de Frota (RF02)</h2>
            <p class="text-muted">Registo, capacidade de carga e monitorização dos veículos da frota ativa.</p>
        </div>
    </div>

    <?php if (!empty($mensagemSucesso)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= sanitizeOutput($mensagemSucesso); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensagemErro)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= sanitizeOutput($mensagemErro); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Registar Novo Veículo</h5>
                <form action="index.php?route=vehicles" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Placa do Veículo</label>
                        <input type="text" name="placa" class="form-control" placeholder="Ex: ABC-1234" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tipo de Veículo</label>
                        <input type="text" name="tipo" class="form-control" placeholder="Ex: Van / Caminhão" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Capacidade de Peso (kg)</label>
                        <input type="number" step="0.01" name="capacidade_kg" class="form-control" placeholder="Ex: 1500.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Capacidade de Volume (m3)</label>
                        <input type="number" step="0.01" name="capacidade_volume" class="form-control" placeholder="Ex: 12.50">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Registar Veículo</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Frotas Registadas</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Placa</th>
                                <th>Tipo</th>
                                <th>Peso Máx (kg)</th>
                                <th>Volume Máx (m3)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($veiculos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Nenhum veículo registado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($veiculos as $v): ?>
                                    <tr>
                                        <td><strong>#<?= $v['id_veiculo']; ?></strong></td>
                                        <td><?= sanitizeOutput($v['placa']); ?></td>
                                        <td><?= sanitizeOutput($v['tipo'] ?? 'N/D'); ?></td>
                                        <td><?= number_format($v['capacidade_kg'], 2, ',', '.'); ?></td>
                                        <td><?= number_format($v['capacidade_volume'] ?? 0, 2, ',', '.'); ?></td>
                                        <td><span class="badge bg-secondary"><?= sanitizeOutput($v['status_veiculo'] ?? 'disponivel'); ?></span></td>
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