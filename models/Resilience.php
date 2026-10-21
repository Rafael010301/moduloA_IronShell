<?php
/**
 * Modelo de Resiliência e Fallback (RF11)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Resilience {

    /**
     * Verifica o estado de saúde do motor preditivo e ativa fallback se necessário
     */
    public static function checkSystemResilience() {
        $db = Database::getConnection();
        
        try {
            // Teste de integridade rápida da base de dados e tabelas críticas
            $stmt = $db->query("SELECT COUNT(*) as total FROM pedidos");
            $result = $stmt->fetch();
            
            if ($result !== false) {
                return [
                    'status' => 'ONLINE',
                    'modo_fallback' => false,
                    'mensagem' => 'Motor preditivo e base de dados operacionais com alta disponibilidade.'
                ];
            }
        } catch (Exception $e) {
            // Registo de contingência ativada por falha de dependência
            self::logFallbackEvent($e->getMessage());
        }

        return [
            'status' => 'FALLBACK_ATIVO',
            'modo_fallback' => true,
            'mensagem' => 'Modo de resiliência acionado. A utilizar rotas heurísticas estáticas locais.'
        ];
    }

    /**
     * Regista eventos de ativação de resiliência para auditoria
     */
    private static function logFallbackEvent($erro) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO alertas (id_pedido, tipo_alerta, mensagem, lido) 
            VALUES (NULL, 'RESILIENCIA_FALLBACK', :msg, 0)
        ");
        $stmt->execute([':msg' => 'Fallback ativado automaticamente devido a anomalia: ' . $erro]);
    }
}