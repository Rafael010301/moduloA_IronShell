<?php
/**
 * Modelo de Rastreamento e Monitorização (Tracking Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Tracking {

    /**
     * Retorna pedidos ativos em rota ou com histórico de localização
     */
    public static function getActiveDeliveries() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT p.*, v.placa, m.nome AS motorista_nome 
            FROM pedidos p
            LEFT JOIN veiculos v ON p.id_veiculo = v.id_veiculo
            LEFT JOIN motoristas m ON p.id_motorista = m.id_motorista
            ORDER BY p.id_pedido DESC
        ");
        return $stmt->fetchAll();
    }
}