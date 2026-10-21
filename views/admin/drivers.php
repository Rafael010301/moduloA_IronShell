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

    $nome        = $_POST['nome'] ?? '';
    $cnhNumero   = $_POST['cnh_numero'] ?? '';
    $cnhCat      = $_POST['cnh_categoria'] ?? '';
    $cnhValidade = $_POST['cnh_validade'] ?? '';
    $telefone    = $_POST['telefone'] ?? '';
    $idVeiculo   = $_POST['id_veiculo'] ?? null;

    if (!empty($nome) && !empty($cnhNumero) && !empty($cnhCat)) {
        try {
            Driver::create($nome, $cnhNumero, $cnhCat, $cnhValidade, $telefone, $idVeiculo);
            $mensagemSucesso = "Motorista registado com sucesso!";
        } catch (PDOException $e) {
            $mensagemErro = "Erro ao registar: O número da CNH já pode estar registado.";
        }
    } else {
        $mensagemErro = "Por favor, preencha todos os campos obrigatórios.";
    }
}

$motoristas = Driver.php ?? Driver::all();
$veiculosDisponiveis = Vehicle::all();
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
            <p class="text-muted">Registe motoristas, valide categorias de CNH e vincule-os aos veículos da frota.</p>
        </div>
    </div>

    <?php if (!empty($mensagemSucesso)): ?>
        <div class="alert alert-success"><?= sanitizeOutput($mensagemSucesso); ?></div>
    <?php endif; ?>

    <?php if (!empty($mensagemErro)): ?>
        <div class="alert alert-danger"><?= sanitizeOutput($mensagemErro); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulário -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Novo Motorista</h5>
                <form action="index.php?route=drivers" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" placeholder="Ex: João da Silva" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Número da CNH</label>
                        <input type="text" class="form-control" name="cnh_numero" placeholder="Ex: 12345678900" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Categoria CNH</label>
                        <select class="form-control" name="cnh_categoria" required>
                            <option value="">Selecione...</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Validade da CNH</label>
                        <input type="date" class="form-control" name="cnh_validade" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Telefone / Contato</label>
                        <input type="text" class="form-control" name="telefone" placeholder="Ex: (11) 98765-4321" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Veículo Atribuído (Opcional)</label>
                        <select class="form-control" name="id_veiculo">
                            <option value="">Nenhum no momento</option>
                            <?php foreach ($veiculosDisponiveis as $v): ?>
                                <option value="<?= $v['id_veiculo']; ?>"><?= sanitizeOutput($v['placa']); ?> (<?= sanitizeOutput($v['tipo']); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registar Motorista</button>
                </form>
            </div>
        </div>

        <!-- Tabela -->
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Motoristas Registados</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CNH</th>
                                <th>Validade</th>
                                <th>Veículo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($motoristas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum motorista registado ainda.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($motoristas as $m): ?>
                                    <tr>
                                        <td><strong><?= sanitizeOutput($m['nome']); ?></strong><br><small class="text-muted"><?= sanitizeOutput($m['telefone']); ?></small></td>
                                        <td><?= sanitizeOutput($m['cnh_numero']); ?> (<strong><?= sanitizeOutput($m['cnh_categoria']); ?></strong>)</td>
                                        <td><?= sanitizeOutput($m['cnh_validade']); ?></td>
                                        <td><?= !empty($m['placa']) ? sanitizeOutput($m['placa']) : '<span class="text-muted">Nenhum</span>'; ?></td>
                                        <td><span class="badge bg-success"><?= sanitizeOutput($m['status_disponibilidade']); ?></span></td>
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