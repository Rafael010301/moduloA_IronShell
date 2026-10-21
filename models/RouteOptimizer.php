<?php
/**
 * Modelo de Otimização de Rotas e IA (RouteOptimizer Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class RouteOptimizer {

    /**
     * Retorna todos os pedidos que estão pendentes para otimização
     */
    public static function getPendingOrders() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM pedidos WHERE status_entrega = 'pendente' ORDER BY prioridade DESC, id_pedido ASC");
        return $stmt->fetchAll();
    }

    /**
     * Retorna frotas/veículos disponíveis
     */
    public static function getAvailableFleet() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM veiculos");
        return $stmt->fetchAll();
    }

    /**
     * Retorna motoristas registados
     */
    public static function getDrivers() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM motoristas");
        return $stmt->fetchAll();
    }

    /**
     * Simula a alocação inteligente e cálculo de risco por IA
     */
    public static function calculateAIROUTE($pedidoId, $veiculoId, $motoristaId) {
        $db = Database::getConnection();
        
        // Simulação de cálculo preditivo de risco de atraso baseado em peso e volume (IA Predict)
        $stmtPedido = $db->prepare("SELECT * FROM pedidos WHERE id_pedido = :id");
        $stmtPedido->execute([':id' => $pedidoId]);
        $pedido = $stmtPedido->fetch();

        // Exemplo de algoritmo preditivo simples: peso maior que 50kg aumenta o risco de atraso simulado
        $riscoAtraso = ($pedido && $pedido['peso_kg'] > 50) ? 18.5 : 4.2;

        // Atualiza o pedido com a alocação e o risco calculado pela IA
        $stmtUpdate = $db->prepare("
            UPDATE pedidos 
            SET id_veiculo = :veiculo, id_motorista = :motorista, status_entrega = 'em_rota', risco_atraso_percentual = :risco 
            WHERE id_pedido = :id
        ");

        return $stmtUpdate->execute([
            ':veiculo'   => $veiculoId,
            ':motorista' => $motoristaId,
            ':risco'     => $riscoAtraso,
            ':id'        => $pedidoId
        ]);
    }
}