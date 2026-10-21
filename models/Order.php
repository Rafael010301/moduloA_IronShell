<?php
/**
 * Modelo de Pedidos e Entregas (Order Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Order {

    /**
     * Lista todos os pedidos registados
     */
    public static function all() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM pedidos ORDER BY id_pedido DESC");
        return $stmt->fetchAll();
    }

    /**
     * Insere um pedido individual ou via importação (com INSERT IGNORE para evitar duplicados)
     */
    public static function create($codigoRastreio, $cliente, $endereco, $peso, $volume, $prioridade) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT IGNORE INTO pedidos (codigo_rastreio, cliente_nome, endereco_destino, peso_kg, volume_m3, prioridade, status_entrega) 
            VALUES (:codigo, :cliente, :endereco, :peso, :volume, :prioridade, 'pendente')
        ");
        
        return $stmt->execute([
            ':codigo'     => trim($codigoRastreio),
            ':cliente'    => trim($cliente),
            ':endereco'   => trim($endereco),
            ':peso'       => floatval($peso),
            ':volume'     => floatval($volume),
            ':prioridade' => trim($prioridade)
        ]);
    }

    /**
     * Elimina um pedido pelo ID
     */
    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM pedidos WHERE id_pedido = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Atualiza o status de entrega do pedido
     */
    public static function updateStatus($id, $novoStatus) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE pedidos SET status_entrega = :status WHERE id_pedido = :id");
        return $stmt->execute([
            ':status' => $novoStatus,
            ':id'     => $id
        ]);
    }
}