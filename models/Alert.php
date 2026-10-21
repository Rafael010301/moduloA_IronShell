<?php
/**
 * Modelo de Alertas e Notificações (Alert Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Alert {

    /**
     * Retorna todos os alertas registados no sistema sem depender de joins restritos
     */
    public static function all() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT * 
            FROM alertas 
            ORDER BY id_alerta DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Cria um novo alerta manual ou automático
     */
    public static function create($tipo, $mensagem) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO alertas (tipo_alerta, mensagem, lido) 
            VALUES (:tipo, :mensagem, 0)
        ");
        return $stmt->execute([
            ':tipo'      => trim($tipo),
            ':mensagem'  => trim($mensagem)
        ]);
    }
}