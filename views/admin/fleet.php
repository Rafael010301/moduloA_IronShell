<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']); // Apenas admin e gestor acedem à frota[cite: 2]
require_once __DIR__ . '/../../models/Vehicle.php';

$mensagemSucesso = '';
$mensagemErro = '';

// Processa o formulário de cadastro se for enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    
    if (!verifyCSRFToken($csrfToken)) {
        die("Falha na validação CSRF.");
    }

    $placa         = $_POST['placa'] ?? '';
    $tipo          = $_POST['tipo'] ?? '';
    $capacidadeKg  = floatval($_POST['capacidade_kg'] ?? 0);
    $capacidadeVol = floatval($_POST['capacidade_volume'] ?? 0);
    $autonomia     = floatval($_POST['autonomia'] ?? 0);
    $manutencao    = $_POST['proxima_manutencao'] ?? '';

    if (!empty($placa) && !empty($tipo) && $capacidadeKg > 0) {
        try {
            Vehicle::create($placa, $tipo, $capacidadeKg, $capacidadeVol, $autonomia, $manutencao);
            $mensagemSucesso = "Veículo cadastrado com sucesso!";
        } catch (PDOException $e) {
            $mensagemErro = "Erro ao cadastrar: A placa informada já pode estar registada.";
        }
    } else {
        $mensagemErro = "Por favor, preencha todos os campos obrigatórios corretamente.";
    }
}

$veiculos = Vehicle::all();
require_once __DIR__ . '/../layouts/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php?route=dashboard">SENN AI-X</a>
        <div class="d-flex align-items-center text-white">
            <a href="index.php?route=dashboard" class="btn btn-outline-light btn-sm me-3">Voltar ao Dashboard</a>
            <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Gestão de Frota e Veículos (RF02)</h2>
            <p class="text-muted">Registe e gira os veículos disponíveis para as rotas de entrega[cite: 2].</p>
        </div>
    </div>

    <?php if (!empty($mensagemSucesso)): ?>
        <div class="alert alert-success"><?= sanitizeOutput($mensagemSucesso); ?></div>
    <?php endif; ?>

    <?php if (!empty($mensagemErro)): ?>
        <div class="alert alert-danger"><?= sanitizeOutput($mensagemErro); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulário de Cadastro -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Novo Veículo</h5>
                <form action="index.php?route=fleet" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Placa</label>
                        <input type="text" class="form-control" name="placa" placeholder="Ex: ABC-1234" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tipo de Veículo</label>
                        <select class="form-control" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="VUC">VUC</option>
                            <option value="Furgao">Furgão</option>
                            <option value="Caminhao Leve">Caminhão Leve</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Capacidade (Kg)</label>
                        <input type="number" step="0.01" class="form-control" name="capacidade_kg" placeholder="Ex: 1500.00" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Capacidade (Volume m³)</label>
                        <input type="number" step="0.01" class="form-control" name="capacidade_volume" placeholder="Ex: 12.5" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Autonomia (Km/L)</label>
                        <input type="number" step="0.01" class="form-control" name="autonomia" placeholder="Ex: 8.5" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Próxima Manutenção</label>
                        <input type="date" class="form-control" name="proxima_manutencao" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registar Veículo</button>
                </form>
            </div>
        </div>

        <!-- Tabela de Veículos Registados -->
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Frota Registada</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Tipo</th>
                                <th>Capacidade</th>
                                <th>Status</th>
                                <th>Manutenção</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($veiculos)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum veículo registado ainda.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($veiculos as $v): ?>
                                    <tr>
                                        <td><strong><?= sanitizeOutput($v['placa']); ?></strong></td>
                                        <td><?= sanitizeOutput($v['tipo']); ?></td>
                                        <td><?= sanitizeOutput($v['capacidade_kg']); ?> kg / <?= sanitizeOutput($v['capacidade_volume']); ?> m³</td>
                                        <td><span class="badge bg-success"><?= sanitizeOutput($v['status_veiculo']); ?></span></td>
                                        <td><?= sanitizeOutput($v['proxima_manutencao']); ?></td>
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