<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor', 'operador']);
require_once __DIR__ . '/../../models/Pod.php';

$mensagemSucesso = '';
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        die("Falha na validação CSRF.");
    }

    $pedidoId = intval($_POST['id_pedido'] ?? 0);
    $statusEntrega = $_POST['status_entrega'] ?? '';
    $observacao = $_POST['observacao'] ?? 'Entrega finalizada com sucesso.';

    if ($pedidoId > 0 && in_array($statusEntrega, ['entregue', 'falha'])) {
        Pod::confirmarEntrega($pedidoId, $statusEntrega, $observacao);
        $mensagemSucesso = "Comprovativo de entrega (POD) registado com sucesso!";
    } else {
        $mensagemErro = "Selecione um pedido válido e o status da entrega.";
    }
}

$pedidosAtivos = Pod::getDeliveriesInTransit();

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
            <h2>Confirmação de Entrega - POD (RF07)</h2>
            <p class="text-muted">Registo digital de comprovativos, assinaturas ou ocorrências de entregas na ponta final.</p>
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
        <!-- Formulário de Registo POD -->
        <div class="col-md-5 mb-4">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Registar Comprovativo (POD)</h5>
                <form action="index.php?route=pod" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Selecionar Pedido em Rota</label>
                        <select name="id_pedido" class="form-select" required>
                            <option value="">Selecione o Pedido...</option>
                            <?php foreach ($pedidosAtivos as $p): ?>
                                <option value="<?= $p['id_pedido']; ?>">
                                    <?= sanitizeOutput($p['codigo_rastreio']); ?> - <?= sanitizeOutput($p['cliente_nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Final da Entrega</label>
                        <select name="status_entrega" class="form-select" required>
                            <option value="entregue">✅ Entregue com Sucesso</option>
                            <option value="falha">❌ Falha na Entrega / Ocorrência</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Observações / Nome do Recebedor</label>
                        <textarea class="form-control" name="observacao" rows="3" placeholder="Ex: Recebido por João (Familiar) com assinatura digital." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold">Concluir e Salvar POD</button>
                </form>
            </div>
        </div>

        <!-- Instruções e Boas Práticas POD -->
        <div class="col-md-7">
            <div class="card p-4 shadow-sm bg-light border-0">
                <h5 class="card-title mb-3 text-dark fw-bold">Diretrizes de Comprovação</h5>
                <p class="text-secondary small">O módulo de Proof of Delivery garante a rastreabilidade jurídica e operacional das operações da SENN AI-X.</p>
                <hr>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2">✔️ Registo imediato do encerramento do ciclo do pedido.</li>
                    <li class="mb-2">✔️ Histórico auditável de ocorrências e justificativas de falhas.</li>
                    <li class="mb-2">✔️ Sincronização automática com os relatórios gerenciais e dashboards.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>