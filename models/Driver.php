<?php
/**
 * Modelo de Motoristas (Driver Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Driver {

    /**
     * Lista todos os motoristas com o veículo associado (se houver)
     * 
     * @return array
     */
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

    /**
     * Regista um novo motorista na base de dados (RF03)
     * 
     * @param string $nome
     * @param string $cnhNumero
     * @param string $cnhCategoria
     * @param string $cnhValidade
     * @param string $telefone
     * @param int|null $idVeiculo
     * @return bool
     */
    public static function create($nome, $cnhNumero, $cnhCategoria, $cnhValidade, $telefone, $idVeiculo) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("
            INSERT INTO motoristas (nome, cnh_numero, cnh_categoria, cnh_validade, telefone, id_veiculo, status_disponibilidade) 
            VALUES (:nome, :cnh_numero, :cnh_categoria, :cnh_validade, :telefone, :id_veiculo, 'disponivel')
        ");
        
        return $stmt->execute([
            ':nome'          => trim($nome),
            ':cnh_numero'    => trim($cnhNumero),
            ':cnh_categoria' => strtoupper(trim($cnhCategoria)),
            ':cnh_validade'  => $cnhValidade,
            ':telefone'      => trim($telefone),
            ':id_veiculo'    => !empty($idVeiculo) ? $idVeiculo : null
        ]);
    }
}