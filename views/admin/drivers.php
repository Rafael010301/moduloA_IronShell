<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/Driver.php';
require_once __DIR__ . '/../../models/Vehicle.php'; // Para listar veículos disponíveis

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
            $mensagemSucesso = "Motorista eliminado com sucesso!";
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
            $mensagemErro = "Preencha os campos obrigatórios.";
        }
    }
}

$motoristas = Driver::all();
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
            <h2>Gestão de Motoristas (RF03)</h2>
            <p class="text-muted">Registo e associação de motoristas à frota de veículos.</p>
        </div>
    </div>

    <?php if (!empty($mensagemSucesso)): ?>
        <div class="alert alert-success"><?= sanitizeOutput($mensagemSucesso); ?></div>
    <?php endif; ?>
    <?php if (!empty($mensagemErro)): ?>
        <div class="alert alert-danger"><?= sanitizeOutput($mensagemErro); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulário de Registo -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Novo Motorista</h5>
                <form action="index.php?route=drivers" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Número da CNH</label>
                        <input type="text" class="form-control" name="cnh" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Categoria CNH</label>
                        <select name="cnh_categoria" class="form-select" required>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Telefone</label>
                        <input type="text" class="form-control" name="telefone">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Veículo Atribuído</label>
                        <select name="id_veiculo" class="form-select">
                            <option value="">Nenhum / Frota Geral</option>
                            <?php foreach ($veiculos as $v): ?>
                                <option value="<?= $v['id_veiculo']; ?>"><?= sanitizeOutput($v['placa']); ?> - <?= sanitizeOutput($v['tipo_veiculo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registar Motorista</button>
                </form>
            </div>
        </div>

        <!-- Tabela de Motoristas -->
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Motoristas Registados</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CNH / Cat</th>
                                <th>Telefone</th>
                                <th>Veículo</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($motoristas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum motorista registado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($motoristas as $m): ?>
                                    <tr>
                                        <td><strong><?= sanitizeOutput($m['nome']); ?></strong></td>
                                        <td><?= sanitizeOutput($m['cnh']); ?> (Cat. <?= sanitizeOutput($m['cnh_categoria']); ?>)</td>
                                        <td><?= sanitizeOutput($m['telefone']); ?></td>
                                        <td><?= !empty($m['placa']) ? sanitizeOutput($m['placa']) : '<span class="text-muted">Nenhum</span>'; ?></td>
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