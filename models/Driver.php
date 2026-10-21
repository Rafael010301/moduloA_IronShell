<?php
/**
 * Modelo de Motoristas (Driver Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Driver {

    public static function all() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT m.*, v.placa 
            FROM motoristas m 
            LEFT JOIN veiculos v ON m.id_veiculo = v.id_veiculo 
            ORDER BY m.id_motorista DESC
        ");
        return $stmt->fetchAll();
    }

    public static function create($nome, $cnh, $cnhCategoria, $telefone, $idVeiculo) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO motoristas (nome, cnh_numero, cnh_categoria, telefone, id_veiculo) 
            VALUES (:nome, :cnh_numero, :categoria, :telefone, :veiculo)
        ");
        return $stmt->execute([
            ':nome'        => trim($nome),
            ':cnh_numero'  => trim($cnh),
            ':categoria'   => trim($cnhCategoria),
            ':telefone'    => trim($telefone),
            ':veiculo'     => !empty($idVeiculo) ? intval($idVeiculo) : null
        ]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM motoristas WHERE id_motorista = :id");
        return $stmt->execute([':id' => $id]);
    }
}