<?php
// controllers/FleetController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/security.php';

class FleetController {

    public function createVehicle() {
        // Apenas Admin e Gestor podem cadastrar frotas
        requireRole(['admin', 'gestor']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $placa       = strtoupper(trim($_POST['placa']));
            $tipo        = sanitizeOutput($_POST['tipo']);
            $cap_kg      = floatval($_POST['capacidade_kg']);
            $cap_vol     = floatval($_POST['capacidade_volume']);
            $autonomia   = floatval($_POST['autonomia']);
            $manutencao  = $_POST['data_manutencao'];

            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO veiculos (placa, tipo, capacidade_kg, capacidade_volume, autonomia_km_l, status_veiculo, proxima_manutencao) 
                                  VALUES (:placa, :tipo, :cap_kg, :cap_vol, :autonomia, 'disponivel', :manutencao)");
            
            $success = $stmt->execute([
                ':placa'      => $placa,
                ':tipo'       => $tipo,
                ':cap_kg'     => $cap_kg,
                ':cap_vol'    => $cap_vol,
                ':autonomia'  => $autonomia,
                ':manutencao' => $manutencao
            ]);

            if ($success) {
                header("Location: index.php?route=fleet&status=success");
                exit();
            }
        }
    }
}