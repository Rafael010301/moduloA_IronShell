<?php
/**
 * Modelo de Veículos (Vehicle Model)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Vehicle {

    /**
     * Lista todos os veículos registados na base de dados
     * 
     * @return array
     */
    public static function all() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM veiculos ORDER BY id_veiculo DESC");
        return $stmt->fetchAll();
    }

    /**
     * Regista um novo veículo na frota (RF02)
     * 
     * @param string $placa
     * @param string $tipo
     * @param float $capacidadeKg
     * @param float $capacidadeVol
     * @param float $autonomia
     * @param string $proximaManutencao
     * @return bool
     */
    public static function create($placa, $tipo, $capacidadeKg, $capacidadeVol, $autonomia, $proximaManutencao) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("INSERT INTO veiculos (placa, tipo, capacidade_kg, capacidade_volume, autonomia_km_l, status_veiculo, proxima_manutencao) 
                              VALUES (:placa, :tipo, :cap_kg, :cap_vol, :autonomia, 'disponivel', :manutencao)");
        
        return $stmt->execute([
            ':placa'      => strtoupper(trim($placa)),
            ':tipo'       => $tipo,
            ':cap_kg'     => $capacidadeKg,
            ':cap_vol'    => $capacidadeVol,
            ':autonomia'  => $autonomia,
            ':manutencao' => $proximaManutencao
        ]);
    }
}