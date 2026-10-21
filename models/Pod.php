<?php
/**
 * Modelo de Comprovação de Entrega / POD (Proof of Delivery)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Pod {

    /**
     * Retorna apenas os pedidos que estão em rota ou pendentes
     */
    public static function getDeliveriesInTransit() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT p.*, v.placa, m.nome AS motorista_nome 
            FROM pedidos p
            LEFT JOIN veiculos v ON p.id_veiculo = v.id_veiculo
            LEFT JOIN motoristas m ON p.id_motorista = m.id_motorista
            WHERE p.status_entrega = 'em_rota' OR p.status_entrega = 'pendente'
            ORDER BY p.id_pedido DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Atualiza o status do pedido para entregue ou falha, registando a data atual
     */
    public static function confirmarEntrega($pedidoId, $statusEntrega, $observacao) {
        $db = Database::getConnection();
        
        // Atualiza o status e a data da entrega realizada utilizando as colunas existentes na tabela
        $stmt = $db->prepare("
            UPDATE pedidos 
            SET status_entrega = :status, 
                data_entrega_realizada = NOW()
            WHERE id_pedido = :id
        ");
        return $stmt->execute([
            ':status' => $statusEntrega,
            ':id'     => intval($pedidoId)
        ]);
    }
}