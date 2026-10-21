-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/10/2026 às 19:54
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `senai_logistica`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alertas`
--

CREATE TABLE `alertas` (
  `id_alerta` int(11) NOT NULL,
  `tipo_alerta` enum('atraso_previsto','veiculo_parado','manutencao_vencendo','cnh_vencendo','rota_desviada') NOT NULL,
  `mensagem` text NOT NULL,
  `status_resolvido` tinyint(1) DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alertas`
--

INSERT INTO `alertas` (`id_alerta`, `tipo_alerta`, `mensagem`, `status_resolvido`, `criado_em`) VALUES
(1, '', 'Utilizador [admin@sennaix.com.br] iniciou sessão no sistema.', 1, '2026-10-21 15:53:05'),
(2, '', 'Utilizador [gestor@sennaix.com.br] executou a otimização de rotas por IA (RF05).', 1, '2026-10-21 16:53:05'),
(3, '', 'Alerta Preditivo (IA): O pedido PED-88105 possui 87% de probabilidade de atraso.', 0, '2026-10-21 17:23:05'),
(4, '', 'Alerta de Frota: O veículo LOG-8899 atingiu a data de revisão preventiva agendada.', 0, '2026-10-21 17:43:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_rastreamento`
--

CREATE TABLE `logs_rastreamento` (
  `id_log` int(11) NOT NULL,
  `id_veiculo` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `velocidade` decimal(5,2) DEFAULT 0.00,
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs_rastreamento`
--

INSERT INTO `logs_rastreamento` (`id_log`, `id_veiculo`, `latitude`, `longitude`, `velocidade`, `data_hora`) VALUES
(1, 1, -23.56141400, -46.65588100, 45.50, '2026-10-21 17:38:05'),
(2, 3, -23.02450000, -45.55620000, 60.20, '2026-10-21 17:43:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `motoristas`
--

CREATE TABLE `motoristas` (
  `id_motorista` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_veiculo` int(11) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `cnh_numero` varchar(20) NOT NULL,
  `cnh_categoria` varchar(5) NOT NULL,
  `cnh_validade` date NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `status_disponibilidade` enum('disponivel','em_rota','folga','inativo') DEFAULT 'disponivel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `motoristas`
--

INSERT INTO `motoristas` (`id_motorista`, `id_usuario`, `id_veiculo`, `nome`, `cnh_numero`, `cnh_categoria`, `cnh_validade`, `telefone`, `status_disponibilidade`) VALUES
(1, 3, 1, 'Roberto Alves', '12345678900', 'D', '2027-08-20', '(12) 99888-1111', 'em_rota'),
(2, NULL, 2, 'João Fernando Souza', '98765432100', 'C', '2028-01-10', '(12) 99777-2222', 'disponivel'),
(3, NULL, 3, 'Luciana Oliveira', '45678912300', 'B', '2026-12-18', '(12) 99666-3333', 'em_rota'),
(4, NULL, 4, 'Marcos Vinicius', '78912345600', 'E', '2027-05-14', '(12) 99555-4444', 'folga');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `codigo_rastreio` varchar(20) NOT NULL,
  `cliente_nome` varchar(100) NOT NULL,
  `endereco_destino` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `janela_entrega_inicio` time NOT NULL,
  `janela_entrega_fim` time NOT NULL,
  `peso_kg` decimal(10,2) NOT NULL,
  `volume_m3` decimal(10,2) NOT NULL,
  `prioridade` int(11) DEFAULT 1,
  `status_entrega` enum('pendente','em_rota','entregue','falha') DEFAULT 'pendente',
  `id_motorista` int(11) DEFAULT NULL,
  `id_veiculo` int(11) DEFAULT NULL,
  `ordem_rota` int(11) DEFAULT NULL,
  `risco_atraso_percentual` decimal(5,2) DEFAULT 0.00,
  `comprovante_foto` varchar(255) DEFAULT NULL,
  `data_entrega_realizada` datetime DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `codigo_rastreio`, `cliente_nome`, `endereco_destino`, `latitude`, `longitude`, `janela_entrega_inicio`, `janela_entrega_fim`, `peso_kg`, `volume_m3`, `prioridade`, `status_entrega`, `id_motorista`, `id_veiculo`, `ordem_rota`, `risco_atraso_percentual`, `comprovante_foto`, `data_entrega_realizada`, `criado_em`) VALUES
(1, 'PED-88101', 'Tech Logistics S.A.', 'Av. Paulista, 1000, São Paulo - SP', -23.56141400, -46.65588100, '08:00:00', '12:00:00', 250.00, 1.20, 1, 'entregue', 1, 1, 1, 5.00, NULL, NULL, '2026-10-21 17:53:05'),
(2, 'PED-88102', 'Indústrias Matarazzo', 'Rua Augusta, 450, São Paulo - SP', -23.55321100, -46.64982100, '10:00:00', '14:00:00', 580.00, 3.50, 2, 'em_rota', 1, 1, 2, 12.50, NULL, NULL, '2026-10-21 17:53:05'),
(3, 'PED-88103', 'Distribuidora Taubaté', 'Av. Independência, 120, Taubaté - SP', -23.02450000, -45.55620000, '09:00:00', '17:00:00', 1100.00, 6.00, 3, 'em_rota', 3, 3, 1, 25.00, NULL, NULL, '2026-10-21 17:53:05'),
(4, 'PED-88104', 'Varejo Central Vale', 'Praça Dom Pedro II, 45, SJC - SP', -23.18940000, -45.88410000, '13:00:00', '18:00:00', 850.00, 4.20, 1, 'pendente', 2, 2, 1, 0.00, NULL, NULL, '2026-10-21 17:53:05'),
(5, 'PED-88105', 'Farmácias Associadas', 'Rua XV de Novembro, Caçapava - SP', -23.10120000, -45.70880000, '08:00:00', '10:00:00', 120.00, 0.80, 3, '', 1, 1, 3, 87.00, NULL, NULL, '2026-10-21 17:53:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `perfil` enum('admin','gestor','despachante','motorista') NOT NULL DEFAULT 'despachante',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha_hash`, `perfil`, `criado_em`) VALUES
(1, 'Carlos Silva (Admin)', 'admin@sennaix.com.br', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1h1.T2v1e7YQW4I4g5QJ2w6h3Kq8G1a', 'admin', '2026-10-21 17:53:05'),
(2, 'Mariana Costa (Gestora)', 'gestor@sennaix.com.br', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1h1.T2v1e7YQW4I4g5QJ2w6h3Kq8G1a', 'gestor', '2026-10-21 17:53:05'),
(3, 'Roberto Alves (Motorista)', 'roberto@sennaix.com.br', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1h1.T2v1e7YQW4I4g5QJ2w6h3Kq8G1a', 'motorista', '2026-10-21 17:53:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id_veiculo` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `capacidade_kg` decimal(10,2) NOT NULL,
  `capacidade_volume` decimal(10,2) NOT NULL,
  `autonomia_km_l` decimal(5,2) NOT NULL,
  `status_veiculo` enum('disponivel','em_rota','em_manutencao') DEFAULT 'disponivel',
  `proxima_manutencao` date NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veiculos`
--

INSERT INTO `veiculos` (`id_veiculo`, `placa`, `tipo`, `capacidade_kg`, `capacidade_volume`, `autonomia_km_l`, `status_veiculo`, `proxima_manutencao`, `criado_em`) VALUES
(1, 'ABC-1234', 'Furgão Mercedes Sprinter', 1500.00, 10.50, 9.80, 'em_rota', '2026-11-15', '2026-10-21 17:53:05'),
(2, 'XYZ-9876', 'Caminhão VUC Iveco Daily', 3500.00, 18.00, 7.50, 'disponivel', '2026-12-01', '2026-10-21 17:53:05'),
(3, 'KHM-4512', 'Van Renault Master', 1200.00, 8.00, 11.20, 'em_rota', '2026-10-30', '2026-10-21 17:53:05'),
(4, 'LOG-8899', 'Caminhão Toco Volkswagen', 6000.00, 32.00, 5.20, 'em_manutencao', '2026-10-22', '2026-10-21 17:53:05');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id_alerta`);

--
-- Índices de tabela `logs_rastreamento`
--
ALTER TABLE `logs_rastreamento`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices de tabela `motoristas`
--
ALTER TABLE `motoristas`
  ADD PRIMARY KEY (`id_motorista`),
  ADD UNIQUE KEY `cnh_numero` (`cnh_numero`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD UNIQUE KEY `codigo_rastreio` (`codigo_rastreio`),
  ADD KEY `id_motorista` (`id_motorista`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id_veiculo`),
  ADD UNIQUE KEY `placa` (`placa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `logs_rastreamento`
--
ALTER TABLE `logs_rastreamento`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `motoristas`
--
ALTER TABLE `motoristas`
  MODIFY `id_motorista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id_veiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `logs_rastreamento`
--
ALTER TABLE `logs_rastreamento`
  ADD CONSTRAINT `logs_rastreamento_ibfk_1` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id_veiculo`) ON DELETE CASCADE;

--
-- Restrições para tabelas `motoristas`
--
ALTER TABLE `motoristas`
  ADD CONSTRAINT `motoristas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  ADD CONSTRAINT `motoristas_ibfk_2` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id_veiculo`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_motorista`) REFERENCES `motoristas` (`id_motorista`) ON DELETE SET NULL,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id_veiculo`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
