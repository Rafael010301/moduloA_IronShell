<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/Order.php';

$mensagemSucesso = '';
$mensagemErro = '';

// Tratamento de Ações (Excluir ou Atualizar Status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        die("Falha na validação CSRF.");
    }

    $acao = $_POST['action'];
    $idPedido = intval($_POST['id_pedido'] ?? 0);

    if ($acao === 'delete' && $idPedido > 0) {
        Order::delete($idPedido);
        header("Location: index.php?route=orders");
        exit;
    }

    if ($acao === 'update_status' && $idPedido > 0) {
        $novoStatus = $_POST['status_entrega'] ?? 'pendente';
        Order::updateStatus($idPedido, $novoStatus);
        header("Location: index.php?route=orders");
        exit;
    }
}

// Processamento de Upload de CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        die("Falha na validação CSRF.");
    }

    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExtension === 'csv') {
            if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
                $totalImportados = 0;
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if (empty($data) || count($data) < 6) continue;
                    
                    $primeiraColuna = strtolower(trim($data[0]));
                    if ($primeiraColuna === 'codigo' || $primeiraColuna === 'rastreio' || $primeiraColuna === 'codigo_rastreio') {
                        continue; 
                    }

                    Order::create($data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
                    $totalImportados++;
                }
                fclose($handle);
                $mensagemSucesso = "Importação concluída! {$totalImportados} pedidos processados.";
            } else {
                $mensagemErro = "Não foi possível ler o arquivo CSV.";
            }
        } else {
            $mensagemErro = "Por favor, envie um arquivo com a extensão .csv válida.";
        }
    } else {
        $mensagemErro = "Selecione um arquivo CSV válido para importação.";
    }
}

$pedidos = Order::all();
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
            <h2>Gestão e Importação de Pedidos (RF04)</h2>
            <p class="text-muted">Importe lotes de entregas via ficheiro CSV para otimização de rotas e controlo de status.</p>
        </div>
    </div>

    <?php if (!empty($mensagemSucesso)): ?>
        <div class="alert alert-success"><?= sanitizeOutput($mensagemSucesso); ?></div>
    <?php endif; ?>

    <?php if (!empty($mensagemErro)): ?>
        <div class="alert alert-danger"><?= sanitizeOutput($mensagemErro); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulário de Importação CSV -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Importar Pedidos (CSV)</h5>
                <form action="index.php?route=orders" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Ficheiro CSV</label>
                        <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                        <div class="form-text small">Formato esperado: Código, Cliente, Endereço, Peso(Kg), Volume(m³), Prioridade</div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Carregar e Importar</button>
                </form>
            </div>
        </div>

        <!-- Tabela de Pedidos -->
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5 class="card-title mb-3">Pedidos Registados</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Rastreio</th>
                                <th>Cliente / Endereço</th>
                                <th>Peso / Vol</th>
                                <th>Prioridade</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pedidos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Nenhum pedido importado ainda.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pedidos as $p): ?>
                                    <tr>
                                        <td><strong><?= sanitizeOutput($p['codigo_rastreio']); ?></strong></td>
                                        <td><?= sanitizeOutput($p['cliente_nome']); ?><br><small class="text-muted"><?= sanitizeOutput($p['endereco_destino']); ?></small></td>
                                        <td><?= sanitizeOutput($p['peso_kg']); ?> kg / <?= sanitizeOutput($p['volume_m3']); ?> m³</td>
                                        <td><span class="badge bg-secondary"><?= sanitizeOutput($p['prioridade']); ?></span></td>
                                        <td>
                                            <form action="index.php?route=orders" method="POST" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="id_pedido" value="<?= $p['id_pedido']; ?>">
                                                <select name="status_entrega" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                    <option value="pendente" <?= $p['status_entrega'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                                    <option value="em_rota" <?= $p['status_entrega'] === 'em_rota' ? 'selected' : ''; ?>>Em Rota</option>
                                                    <option value="entregue" <?= $p['status_entrega'] === 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                                                    <option value="falha" <?= $p['status_entrega'] === 'falha' ? 'selected' : ''; ?>>Falha</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <form action="index.php?route=orders" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja apagar este pedido?');">
                                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_pedido" value="<?= $p['id_pedido']; ?>">
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