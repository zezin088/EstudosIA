-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/09/2025 às 02:46
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_usuarios`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `arvore_escolhida` int(11) DEFAULT NULL,
  `ultimo_login` datetime DEFAULT current_timestamp(),
  `token` varchar(255) DEFAULT NULL,
  `expira_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `biografia`, `foto`, `arvore_escolhida`, `ultimo_login`, `token`, `expira_token`) VALUES
(4, '', 'beatriz@gmail.com', '$2y$10$27rg7J1YQ9hSdb59AhTUle94ITQWOuvS6ILvpl7d0MODLB/ExkXbu', NULL, NULL, NULL, '2025-08-18 09:19:03', NULL, NULL),
(13, 'Ana', 'ana@gmail.com', '$2y$10$qK9NpLu6OL0OxxbpLGug9e28WLqGG5QFGpgvpxJBXoy4Gfoa51FJS', '', 'imagens/usuarios/68a30cd4d0e8b.jpg', NULL, '2025-08-18 09:19:03', NULL, NULL),
(14, 'wenderson', 'wenderson.souza@gmail.com', '$2y$10$JTjk3KlPbsViCn9Yd9gjCOoDmmLkOp/TEA3pK2q4XkcTusKZxEzN.', '', 'imagens/usuarios/683447d84b17e.jpg', NULL, '2025-08-18 09:19:03', NULL, NULL),
(21, 'Sabrina', 'sabrina@gmail.com', '$2y$10$IBp0mmbPZVPtxwpW/xD6W.L7.LnnFwDNbokehFMaygMa4.3Jot5Cy', NULL, NULL, NULL, '2025-08-29 10:27:41', NULL, NULL),
(23, 'João', 'joao@gmail.com', '1234', NULL, NULL, NULL, '2025-08-29 10:33:06', NULL, NULL),
(24, 'Ana Beatriz', 'anabeatrizmarquescezar@gmail.com', '$2y$10$AhQfU4EUtx1YcZJDuESmRO2wDUYLHufR2ruQfHXhX8jw8JDRscAL.', NULL, NULL, NULL, '2025-08-31 23:31:46', 'c96a38c876e82bf8887e5e7e1a447fbb', '2025-09-01 05:34:59');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
