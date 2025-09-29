-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/09/2025 às 17:33
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

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
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `data_evento` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `data_evento`, `hora_inicio`, `hora_fim`) VALUES
(2, 'fsf', '2025-09-01', '03:33:00', '03:33:00'),
(6, 'dddd', '2025-09-30', '03:33:00', '03:33:00'),
(7, 'dsdsa', '2025-09-30', '04:55:00', '05:55:00'),
(8, 'ffff', '2025-09-30', '03:33:00', '03:33:00'),
(9, '3r3r3', '2025-09-30', '13:11:00', '14:13:00'),
(10, 'adda', '2025-09-30', '14:13:00', '15:15:00'),
(11, 'www', '2025-09-23', '06:36:00', '14:15:00'),
(12, '333', '2025-09-23', '03:33:00', '06:06:00'),
(13, '333333', '2025-09-16', '03:33:00', '05:35:00'),
(14, '666', '2025-09-26', '06:59:00', '10:02:00'),
(15, '333', '2025-09-26', '03:33:00', '04:04:00'),
(16, '3333', '2025-09-12', '13:18:00', '15:20:00'),
(17, 'ffffggr', '2025-09-06', '06:59:00', '08:55:00'),
(18, 'dwddw', '2025-09-05', '03:45:00', '06:59:00'),
(19, '55555', '2025-10-31', '05:55:00', '07:59:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
