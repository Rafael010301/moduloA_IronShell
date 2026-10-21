<?php
/**
 * Modelo do Assistente Virtual / Chatbot (RF13)
 * SENAI DEV EXPERIENCE 2026 - Módulo A
 */

require_once __DIR__ . '/../config/database.php';

class Chatbot {

    public static function processarMensagem($pergunta) {
        $db = Database::getConnection();
        $texto = mb_strtolower(trim($pergunta), 'UTF-8');

        // Intenção 1: Consultar status de um pedido específico por código de rastreio
        if (strpos($texto, 'pedido') !== false || strpos($texto, 'rastreio') !== false || strpos($texto, 'status') !== false) {
            // Tenta extrair um código ou palavra após a palavra chave
            preg_match('/[a-zA-Z0-9\-_]{4,}/', $texto, $matches);
            
            if (isset($matches[0]) && !in_array($matches[0], ['pedido', 'rastreio', 'status', 'qual', 'o'])) {
                $codigo = $matches[0];
                $stmt = $db->prepare("SELECT * FROM pedidos WHERE codigo_rastreio LIKE :codigo");
                $stmt->execute([':codigo' => "%$codigo%"]);
                $pedido = $stmt->fetch();

                if ($pedido) {
                    return "O pedido <strong>{$pedido['codigo_rastreio']}</strong> para o cliente <strong>{$pedido['cliente_nome']}</strong> encontra-se atualmente com o status: <strong>" . strtoupper($pedido['status_entrega']) . "</strong>.";
                } else {
                    return "Não encontrei nenhum registo de pedido com o código ou termo correspondente a '{$codigo}'.";
                }
            } else {
                // Retornar um resumo geral dos pedidos
                $total = $db->query("SELECT COUNT(*) as t FROM pedidos")->fetch()['t'];
                $entregues = $db->query("SELECT COUNT(*) as t FROM pedidos WHERE status_entrega = 'entregue'")->fetch()['t'];
                return "Atualmente existem <strong>{$total}</strong> pedidos registados no sistema, sendo que <strong>{$entregues}</strong> já foram concluídos com sucesso. Pode especificar o código de rastreio para detalhes de um pedido.";
            }
        }

        // Intenção 2: Consultar frota de veículos
        if (strpos($texto, 'frota') !== false || strpos($texto, 'veiculo') !== false || strpos($texto, 'caminhao') !== false) {
            $totalVeiculos = $db->query("SELECT COUNT(*) as t FROM veiculos")->fetch()['t'];
            return "A frota ativa possui atualmente <strong>{$totalVeiculos}</strong> veículos registados e dimensionados no sistema para otimização de cargas.";
        }

        // Intenção 3: Consultar motoristas
        if (strpos($texto, 'motorista') !== false || strpos($texto, 'condutor') !== false) {
            $totalMotoristas = $db->query("SELECT COUNT(*) as t FROM motoristas")->fetch()['t'];
            return "Existem <strong>{$totalMotoristas}</strong> motoristas registados na base de dados com respetiva validação de CNH e vínculos operacionais.";
        }

        // Intenção 4: Ajuda geral / Funcionalidades
        if (strpos($texto, 'ajuda') !== false || strpos($texto, 'funcionalidades') !== false || strpos($texto, 'o que faz') !== false) {
            return "O <strong>SENN AI-X</strong> é uma plataforma de gestão logística avançada. Posso ajudá-lo a consultar o status de pedidos, verificar dados da frota de veículos, indicadores gerenciais (KPIs) ou orientar sobre os módulos de roteirização e POD.";
        }

        // Resposta Padrão / Fallback inteligente em linguagem natural
        return "Compreendi a sua dúvida, mas para obter maior precisão, tente perguntar sobre: 'status do pedido [código]', 'quantos veículos tem a frota', 'motoristas registados' ou 'ajuda do sistema'.";
    }
}