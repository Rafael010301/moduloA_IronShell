<?php
/**
 * Modelo de Indicadores Gerenciais / KPIs
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Kpi {

    public static function getMetrics() {
        $db = Database::getConnection();

        // Total de Pedidos por Status
        $stmtStatus = $db->query("
            SELECT status_entrega, COUNT(*) as total 
            FROM pedidos 
            GROUP BY status_entrega
        ");
        $statusCounts = $stmtStatus->fetchAll(PDO::FETCH_KEY_PAIR);

        // Média de Risco de Atraso
        $stmtRisco = $db->query("SELECT AVG(risco_atraso_percentual) as media_risco FROM pedidos");
        $mediaRisco = $stmtRisco->fetch()['media_risco'] ?? 0;

        // Total de Veículos e Motoristas
        $stmtVeiculos = $db->query("SELECT COUNT(*) as total FROM veiculos");
        $totalVeiculos = $stmtVeiculos->fetch()['total'] ?? 0;

        $stmtMotoristas = $db->query("SELECT COUNT(*) as total FROM motoristas");
        $totalMotoristas = $stmtMotoristas->fetch()['total'] ?? 0;

        return [
            'total_pedidos'   => array_sum($statusCounts),
            'pendentes'       => $statusCounts['pendente'] ?? 0,
            'em_rota'         => $statusCounts['em_rota'] ?? 0,
            'entregues'       => $statusCounts['entregue'] ?? 0,
            'falhas'          => $statusCounts['falha'] ?? 0,
            'media_risco'     => round($mediaRisco, 1),
            'total_veiculos'  => $totalVeiculos,
            'total_motoristas'=> $totalMotoristas
        ];
    }
}