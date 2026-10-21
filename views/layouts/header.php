<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Função de segurança global para sanitização de saída
 */
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
</head>
<body class="bg-light">

<!-- Integracao Global do Assistente Virtual (RF13) -->
<?php 
$widgetPath = __DIR__ . '/../components/chatbot_widget.php';
if (file_exists($widgetPath)) {
    require_once $widgetPath;
}
?>