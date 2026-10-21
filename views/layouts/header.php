<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('sanitizeOutput')) {
    function sanitizeOutput($data) {
        return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENN AI-X - Plataforma de Gestão Logística</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js para Gráficos Dinâmicos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; position: fixed; top: 0; left: 0; width: 260px; z-index: 100; transition: all 0.3s; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; font-size: 0.95rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #343a40; border-left: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; }
        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; min-height: auto; }
            .main-content { margin-left: 0; padding: 15px; }
        }
    </style>
</head>
<body>

<!-- Menu Lateral (Sidebar) Corporativo -->
<div class="sidebar d-flex flex-column p-3 shadow">
    <a href="index.php?route=dashboard" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold">SENN AI-X</span>
    </a>
    <hr class="text-secondary">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="index.php?route=dashboard" class="nav-link">Painel Operacional</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=vehicles" class="nav-link">Gestão de Frota (RF02)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=drivers" class="nav-link">Gestão de Motoristas (RF03)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=orders" class="nav-link">Gestão de Pedidos (RF04)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=optimizer" class="nav-link">Otimização de Rotas - IA (RF05)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=tracking" class="nav-link">Rastreamento Real-Time (RF06)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=pod" class="nav-link">Comprovação POD (RF07)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=alerts" class="nav-link">Alertas e Anomalias (RF11)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=kpis" class="nav-link">Indicadores KPIs (RF10)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=reports" class="nav-link">Relatórios e Exportação</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=audit" class="nav-link">Auditoria de Logs (RF12)</a></li>
        <li class="nav-item mb-1"><a href="index.php?route=maintenance" class="nav-link">Manutenção Preventiva (RF13)</a></li>
    </ul>
    <hr class="text-secondary">
    <div class="dropdown">
        <span class="d-block text-white text-decoration-none small mb-2">
            Utilizador: <strong><?= sanitizeOutput($_SESSION['usuario_nome'] ?? 'Operador'); ?></strong>
        </span>
        <a href="index.php?route=logout" class="btn btn-outline-danger btn-sm w-100 fw-bold">Sair do Sistema</a>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="main-content">

<!-- Inclusão Global do Widget do Chatbot (RF13) -->
<?php 
$widgetPath = __DIR__ . '/../components/chatbot_widget.php';
if (file_exists($widgetPath)) {
    require_once $widgetPath;
}
?>