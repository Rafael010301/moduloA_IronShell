<?php
require_once __DIR__ . '/../../includes/security.php';
requireRole(['admin', 'gestor']);
require_once __DIR__ . '/../../models/RouteOptimizer.php';

$mensagemSucesso = '';
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        die("Falha na validação CSRF.");
    }

    $pedidoId = intval($_POST['id_pedido'] ?? 0);
    $veiculoId = intval($_POST['id_veiculo'] ?? 0);
    $motoristaId = intval($_POST['id_motorista'] ?? 0);

    if ($pedidoId > 0 && $veiculoId > 0 && $motoristaId > 0) {
        RouteOptimizer::calculateAIROUTE($pedidoId, $veiculoId, $motoristaId);
        $mensagemSucesso = "Rota otimizada com sucesso e alocada via IA Predict!";
    } else {
        $mensagemErro = "Selecione um pedido, um veículo e um motorista válidos.";
    }
}

$pedidosPendentes = RouteOptimizer::getPendingOrders();
$veiculos = RouteOptimizer::getAvailableFleet();
$motoristas = RouteOptimizer::getDrivers();

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
            <h2>Otimização de Rotas e IA Predict (RF05)</h2>
            <p class="text-muted">Atribuição inteligente de pedidos pendentes à frota e motoristas com análise preditiva de risco.</p>
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
        <!-- Formulário de Alocação Inteligente -->
        <div class="col-md-5 mb-4">
            <div class="card p-4 shadow-sm border-0">
                <h5 class="card-title mb-3 fw-bold">Atribuição de Rota por IA</h5>
                <form action="index.php?route=optimizer" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pedido Pendente</label>
                        <select name="id_pedido" class="form-select" required>
                            <option value="">Selecione o Pedido...</option>
                            <?php foreach ($pedidosPendentes as $p): ?>
                                <option value="<?= $p['id_pedido']; ?>">
                                    <?= sanitizeOutput($p['codigo_rastreio']); ?> - <?= sanitizeOutput($p['cliente_nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Veículo / Frota</label>
                        <select name="id_veiculo" class="form-select" required>
                            <option value="">Selecione o Veículo...</option>
                            <?php foreach ($veiculos as $v): ?>
                                <option value="<?= $v['id_veiculo']; ?>">
                                    <?= sanitizeOutput($v['placa']); ?> (<?= sanitizeOutput($v['tipo_veiculo'] ?? $v['modelo'] ?? $v['tipo'] ?? 'Frota Geral'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Motorista</label>
                        <select name="id_motorista" class="form-select" required>
                            <option value="">Selecione o Motorista...</option>
                            <?php foreach ($motoristas as $m): ?>
                                <option value="<?= $m['id_motorista']; ?>">
                                    <?= sanitizeOutput($m['nome']); ?> (CNH: <?= sanitizeOutput($m['cnh_numero'] ?? $m['cnh'] ?? 'N/A'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">Otimizar e Despachar Rota</button>
                </form>
            </div>
        </div>

        <!-- Explicação do Algoritmo Predict -->
        <div class="col-md-7">
            <div class="card p-4 shadow-sm bg-light border-0">
                <h5 class="card-title mb-3 text-dark fw-bold">Painel de Inteligência Artificial</h5>
                <p class="text-secondary small">O motor de IA analisa variáveis de carga, peso e histórico para calcular automaticamente a probabilidade de atraso e sugerir a melhor distribuição de entregas.</p>
                <hr>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2">✔️ Cruzamento automático de restrições de CNH e peso do veículo.</li>
                    <li class="mb-2">✔️ Cálculo dinâmico de risco de atraso por machine learning simulado.</li>
                    <li class="mb-2">✔️ Integração nativa com as credenciais de segurança do sistema.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>