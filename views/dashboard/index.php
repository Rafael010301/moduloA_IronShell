<?php
require_once __DIR__ . '/../../includes/security.php';
requireAuth();

// Recupera os dados da sessão com segurança
$nomeUsuario = $_SESSION['usuario_nome'] ?? $_SESSION['user_nome'] ?? $_SESSION['email'] ?? 'Utilizador';
$roleUsuario = $_SESSION['role'] ?? $_SESSION['tipo'] ?? 'operador';

require_once __DIR__ . '/../layouts/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?route=dashboard">SENN AI-X <span class="badge bg-primary fs-6">Logística</span></a>
        <div class="d-flex align-items-center text-white">
            <span class="me-3 small text-light">Utilizador: <strong><?= sanitizeOutput($nomeUsuario); ?></strong> (<?= strtoupper(sanitizeOutput($roleUsuario)); ?>)</span>
            <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Painel Operacional Central</h2>
            <p class="text-muted">Bem-vindo ao sistema de gestão logística avançada. Selecione um módulo abaixo para gerir as operações.</p>
        </div>
    </div>

    <!-- Grelha de Módulos da Plataforma -->
    <div class="row g-4">
        
        <!-- Frota (RF02) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">Gestão de Frota</h5>
                    <p class="card-text text-muted small">Registo, capacidade de carga e monitorização dos veículos da frota ativa.</p>
                    <a href="index.php?route=vehicles" class="btn btn-outline-primary btn-sm w-100 fw-semibold">Aceder à Frota</a>
                </div>
            </div>
        </div>

        <!-- Motoristas (RF03) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">Gestão de Motoristas</h5>
                    <p class="card-text text-muted small">Registo de condutores, validação de CNH e atribuição de veículos.</p>
                    <a href="index.php?route=drivers" class="btn btn-outline-primary btn-sm w-100 fw-semibold">Aceder a Motoristas</a>
                </div>
            </div>
        </div>

        <!-- Pedidos / CSV (RF04) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">Gestão de Pedidos</h5>
                    <p class="card-text text-muted small">Importação em lote via CSV, listagem e gestão de destinos de entrega.</p>
                    <a href="index.php?route=orders" class="btn btn-outline-primary btn-sm w-100 fw-semibold">Aceder aos Pedidos</a>
                </div>
            </div>
        </div>

        <!-- Otimização IA (RF05) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success">Otimização de Rotas (IA)</h5>
                    <p class="card-text text-muted small">Motor preditivo de alocação de cargas, cálculo de risco e despacho inteligente.</p>
                    <a href="index.php?route=optimizer" class="btn btn-outline-success btn-sm w-100 fw-semibold">Aceder ao Otimizador</a>
                </div>
            </div>
        </div>

        <!-- Rastreamento (RF06) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success">Rastreamento em Tempo Real</h5>
                    <p class="card-text text-muted small">Monitorização de entregas em curso, status da frota e percentuais de risco.</p>
                    <a href="index.php?route=tracking" class="btn btn-outline-success btn-sm w-100 fw-semibold">Aceder ao Rastreamento</a>
                </div>
            </div>
        </div>

        <!-- POD / Confirmação de Entrega (RF07) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success">Confirmação de Entrega (POD)</h5>
                    <p class="card-text text-muted small">Registo digital de comprovativos, assinaturas e encerramento de entregas.</p>
                    <a href="index.php?route=pod" class="btn btn-outline-success btn-sm w-100 fw-semibold">Aceder ao POD</a>
                </div>
            </div>
        </div>

        <!-- Alertas (RF08) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-danger">Alertas e Anomalias</h5>
                    <p class="card-text text-muted small">Notificações automáticas de desvios de rota, atrasos previstos e ocorrências.</p>
                    <a href="index.php?route=alerts" class="btn btn-outline-danger btn-sm w-100 fw-semibold">Aceder aos Alertas</a>
                </div>
            </div>
        </div>
        <!-- KPIs Gerenciais (RF09) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-dark">Indicadores Gerenciais (KPIs)</h5>
                    <p class="card-text text-muted small">Métricas estratégicas de volume, taxa de sucesso de entregas e médias de risco.</p>
                    <a href="index.php?route=kpis" class="btn btn-outline-dark btn-sm w-100 fw-semibold">Aceder aos KPIs</a>
                </div>
            </div>
        </div>
        <!-- Relatórios e Exportação (RF10) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-dark">Relatórios e Exportação</h5>
                    <p class="card-text text-muted small">Geração de relatórios operacionais em CSV para auditoria e controle externo.</p>
                    <a href="index.php?route=reports" class="btn btn-outline-dark btn-sm w-100 fw-semibold">Aceder aos Relatórios</a>
                </div>
            </div>
        </div>
        <!-- Resiliência e Fallback (RF11) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-dark">Resiliência e Fallback</h5>
                    <p class="card-text text-muted small">Monitorização de redundância de sistemas e ativação de rotas de contingência.</p>
                    <a href="index.php?route=resilience" class="btn btn-outline-dark btn-sm w-100 fw-semibold">Aceder à Resiliência</a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>