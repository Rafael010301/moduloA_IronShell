# SENN AI-X - Plataforma de Gestão Logística (Última Milha)

Plataforma corporativa de gestão logística de última milha (last-mile) desenvolvida para o desafio **SENAI DEV EXPERIENCE 2026 - Módulo A**. O sistema integra planeamento avançado de rotas, monitorização em tempo real, previsão de risco por inteligência artificial heurística, assistente virtual por linguagem natural e auditoria de conformidade operacional.

---

## 1. Arquitetura e Tecnologias Utilizadas

* **Arquitetura**: Padrão MVC (Model-View-Controller) estruturado em PHP puro.
* **Backend**: PHP 8.x com PDO (PHP Data Objects) para persistência segura e mitigação de vulnerabilidades (SQL Injection, XSS, CSRF).
* **Base de Dados**: MySQL / MariaDB via XAMPP.
* **Frontend**: Bootstrap 5, JavaScript assíncrono (Fetch API) e Chart.js para gráficos gerenciais.
* **Versionamento**: Git e GitHub corporativo.

---

## 2. Estratégia de Inteligência Artificial e Roteirização

Conforme as diretrizes técnicas do projeto, a solução opera com alta resiliência e autonomia total offline:

* **Motor Preditivo de Risco e Demanda (RF05 / RF08 / RF09)**: Baseia-se em heurísticas locais em PHP/SQL que calculam probabilidades de atraso cruzando janelas de entrega, peso de carga e capacidade dos veículos.
* **Otimização de Rotas**: Implementa o agrupamento logístico inspirado em heurísticas do Problema do Caixeiro Viajante (TSP / VRP) adaptadas para alocação inteligente de frotas e motoristas.
* **Assistente Virtual (SENN AI-X Helper - RF13)**: Chatbot integrado na interface que interpreta intenções em linguagem natural através de correspondência de padrões (*pattern matching*) e executa consultas relacionais diretas na base de dados (status de pedidos, frotas e motoristas).
* **Modo Resiliência e Fallback (RNF06)**: Em caso de indisponibilidade de componentes ou latência de rede, o sistema comuta automaticamente para rotas heurísticas estáticas locais, garantindo continuidade operacional ininterrupta.

---

## 3. Requisitos Funcionais Implementados (RF01 - RF13)

1. **RF01**: Cadastro e Autenticação Segura com perfis diferenciados (Admin, Gestor, Operador).
2. **RF02**: Gestão de Frota (Veículos, capacidade em kg e volume, status e manutenções).
3. **RF03**: Gestão de Motoristas (Validação de CNH e vínculos operacionais).
4. **RF04**: Registo e Importação em Lote (CSV) de Pedidos e Entregas.
5. **RF05**: Roteirização e Otimização baseada em heurísticas.
6. **RF06**: Rastreamento e Monitorização em Tempo Real.
7. **RF07**: Comprovação de Entrega Digital (POD com geolocalização e evidência).
8. **RF08 & RF09**: Previsão de Demanda e Deteção Antecipada de Risco de Atraso.
9. **RF10**: Dashboard Gerencial e Indicadores com exportação de relatórios em CSV.
10. **RF11 & RNF06**: Alertas Automáticos e Modo Resiliência / Fallback.
11. **RF12**: Registo de Logs e Auditoria de Atividades do Sistema.
12. **RF13**: Assistente Virtual SENN AI-X Helper integrado via widget flutuante.

---

## 4. Instruções de Instalação e Execução

1. **Pré-requisitos**:
   * Instalar o **XAMPP** (com PHP 8.x e MySQL/MariaDB).
   * Clonar ou extrair o projeto para o diretório `C:\xampp\htdocs\moduloA_SennAiX`.

2. **Configuração da Base de Dados**:
   * Iniciar os serviços **Apache** e **MySQL** no painel do XAMPP.
   * Aceder ao `http://localhost/phpmyadmin/`.
   * Criar uma base de dados com o nome exato: `senai_logistica`.
   * Importar o esquema estrutural e tabelas necessárias (`pedidos`, `veiculos`, `motoristas`, `alertas`, `usuarios`).

3. **Configuração de Conexão**:
   * O ficheiro de configuração encontra-se em `config/database.php`. Garanta que as credenciais do PDO estão corretas:
     ```php
     $host = 'localhost';
     $db   = 'senai_logistica';
     $user = 'root';
     $pass = '';
     ```

4. **Acesso à Aplicação**:
   * Abra o navegador e aceda a:
     `http://localhost/moduloA_SennAiX/index.php`