<?php
/**
 * Mecanismo de Modo Fallback para IA e Mapas (RNF06)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

/**
 * Verifica a disponibilidade de um serviço externo (como API de IA ou Mapas)
 * 
 * @param string $url URL do serviço para teste de conexão rápida
 * @return bool Retorna true se online, false se estiver offline (Fallback ativado)
 */
function checkExternalService($url = "https://api.openai.com") {
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_NOBODY, true);
    curl_setopt($handle, CURLOPT_TIMEOUT, 3); // Timeout rápido de 3 segundos
    curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);

    // Se retornar código HTTP válido, o serviço está online
    return ($httpCode >= 200 && $httpCode < 400);
}

/**
 * Roteirização de Fallback (Caso a API de rotas/IA caia)
 * Utiliza cálculo estático básico por distância euclidiana ou ordem de cadastro
 * 
 * @param array $orders Lista de pedidos
 * @return array Lista ordenada por método de contingência
 */
function fallbackRouteCalculation(array $orders) {
    // Ordenação simples por ID ou prioridade como contingência operacional
    usort($orders, function($a, $b) {
        return ($a['prioridade'] ?? 0) <=> ($b['prioridade'] ?? 0);
    });
    
    return [
        'modo' => 'FALLBACK_ATIVO',
        'mensagem' => 'Serviços externos de IA/Mapas indisponíveis. Rotas geradas por algoritmo de contingência local.',
        'entregas_ordenadas' => $orders
    ];
}