-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 29-Set-2020 às 08:46
-- Versão do servidor: 10.1.44-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SCAIND`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `acaolog`
--

CREATE TABLE `acaolog` (
  `id_acao` int(2) NOT NULL,
  `nome_acao` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `acaolog`
--

INSERT INTO `acaolog` (`id_acao`, `nome_acao`) VALUES
(2, 'Criação de Cadastro'),
(1, 'Criação de Setor'),
(3, 'Edição de Cadastro'),
(6, 'Edição de Permissões'),
(4, 'Edição de Setor'),
(7, 'Exclusão de Cadastro'),
(5, 'Visualização');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastros`
--

CREATE TABLE `cadastros` (
  `id_usuario` int(4) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `nome_sistema` varchar(22) DEFAULT NULL,
  `senha_sistema` varchar(20) DEFAULT NULL,
  `nome_email` varchar(50) DEFAULT NULL,
  `senha_email` varchar(20) DEFAULT NULL,
  `setor` int(2) DEFAULT NULL,
  `obs` varchar(200) DEFAULT NULL,
  `acesso` varchar(20) DEFAULT NULL,
  `permisso` varchar(10) DEFAULT NULL,
  `estado` int(2) DEFAULT NULL,
  `rg` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log`
--

CREATE TABLE `log` (
  `id_usuario` int(4) DEFAULT NULL,
  `horario` datetime NOT NULL,
  `acao` int(2) DEFAULT NULL,
  `nome_usuario` varchar(20) DEFAULT NULL,
  `log` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessao`
--

CREATE TABLE `sessao` (
  `nome_sistema` varchar(25) NOT NULL,
  `sessao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `setor`
--

CREATE TABLE `setor` (
  `id_setor` int(2) NOT NULL,
  `nome_setor` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acaolog`
--
ALTER TABLE `acaolog`
  ADD PRIMARY KEY (`id_acao`),
  ADD UNIQUE KEY `id_acao` (`id_acao`),
  ADD UNIQUE KEY `nome_acao` (`nome_acao`);

--
-- Indexes for table `cadastros`
--
ALTER TABLE `cadastros`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `sessao`
--
ALTER TABLE `sessao`
  ADD PRIMARY KEY (`nome_sistema`);

--
-- Indexes for table `setor`
--
ALTER TABLE `setor`
  ADD PRIMARY KEY (`id_setor`),
  ADD UNIQUE KEY `id_setor` (`id_setor`),
  ADD UNIQUE KEY `nome_setor` (`nome_setor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acaolog`
--
ALTER TABLE `acaolog`
  MODIFY `id_acao` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `cadastros`
--
ALTER TABLE `cadastros`
  MODIFY `id_usuario` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1095;
--
-- AUTO_INCREMENT for table `setor`
--
ALTER TABLE `setor`
  MODIFY `id_setor` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
