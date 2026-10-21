<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/Driver.php';
require_once __DIR__ . '/../../models/Vehicle.php';

$mensagemSucesso = '';
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        die("Falha na validação CSRF.");
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id_motorista'] ?? 0);
        if ($id > 0) {
            Driver::delete($id);
            $mensagemSucesso = "Motorista removido com sucesso!";
        }
    } else {
        $nome = $_POST['nome'] ?? '';
        $cnh = $_POST['cnh'] ?? '';
        $categoria = $_POST['cnh_categoria'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $veiculo = $_POST['id_veiculo'] ?? null;

        if (!empty($nome) && !empty($cnh)) {
            Driver::create($nome, $cnh, $categoria, $telefone, $veiculo);
            $mensagemSucesso = "Motorista registado com sucesso!";
        } else {
            $mensagemErro = "Preencha os campos obrigatórios (Nome e CNH).";
        }
    }
}

$motoristas = Driver::all();
$veiculos = Vehicle::all();

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
            <h2>Gestão de Motoristas (RF03)</h2>
            <p class="text-muted">Registo e associação de motoristas à frota de veículos.</p>
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
        <!-- Formulário de Registo -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Novo Motorista</h5>
                <form action="index.php?route=drivers" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nome Completo *</label>
                        <input type="text" class="form-control" name="nome" placeholder="Ex: Carlos Eduardo Silva" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Número da CNH *</label>
                        <input type="text" class="form-control" name="cnh" placeholder="Ex: 12345678900" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Categoria CNH</label>
                        <select name="cnh_categoria" class="form-select" required>
                            <option value="B">B - Automóveis</option>
                            <option value="C">C - Camiões</option>
                            <option value="D">D - Autocarros/Vans</option>
                            <option value="E">E - Articulados</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Telefone</label>
                        <input type="text" class="form-control" name="telefone" placeholder="Ex: (11) 98765-4321">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Veículo Atribuído</label>
                        <select name="id_veiculo" class="form-select">
                            <option value="">Nenhum / Frota Geral</option>
                            <?php foreach ($veiculos as $v): ?>
                                <option value="<?= $v['id_veiculo']; ?>">
                                    <?= sanitizeOutput($v['placa']); ?> (<?= sanitizeOutput($v['modelo'] ?? $v['tipo_veiculo'] ?? 'Veículo'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">Registar Motorista</button>
                </form>
            </div>
        </div>

        <!-- Tabela de Motoristas Registados -->
        <div class="col-md-8">
            <div class="card p-3 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Motoristas Registados</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>CNH / Categoria</th>
                                <th>Telefone</th>
                                <th>Veículo</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($motoristas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Nenhum motorista registado até ao momento.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($motoristas as $m): ?>
                                    <tr>
                                        <td><strong><?= sanitizeOutput($m['nome']); ?></strong></td>
                                        <td>
                                            <?= sanitizeOutput($m['cnh_numero'] ?? $m['cnh'] ?? 'N/A'); ?> 
                                            <span class="badge bg-secondary ms-1">Cat. <?= sanitizeOutput($m['cnh_categoria'] ?? 'B'); ?></span>
                                        </td>
                                        <td><?= !empty($m['telefone']) ? sanitizeOutput($m['telefone']) : '<span class="text-muted">-</span>'; ?></td>
                                        <td>
                                            <?php if (!empty($m['placa'])): ?>
                                                <span class="badge bg-info text-dark"><?= sanitizeOutput($m['placa']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Nenhum</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <form action="index.php?route=drivers" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja apagar este motorista?');">
                                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_motorista" value="<?= $m['id_motorista']; ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Apagar</button>
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