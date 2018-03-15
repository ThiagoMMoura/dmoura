-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 24-Dez-2016 às 00:34
-- Versão do servidor: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dmoura`
--
-- CREATE DATABASE IF NOT EXISTS `dmoura` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
-- USE `dmoura`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `alias` varchar(50) NOT NULL, -- Caracteres permitidos: "abcdefghijklmnopqrstuvxwyz_0123456789"
    `email` varchar(150) DEFAULT '',
    `senha` varchar(150) NOT NULL,
    `nivel` int(1) NOT NULL DEFAULT '4',
    PRIMARY KEY (`id`),
    UNIQUE KEY `un_alias` (`alias`),
    UNIQUE KEY `un_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `user` (`alias`,`senha`,`nivel`) VALUES
    ('WebMaster','1c9e899ab77610223649760332ddfee6ec0a9ab1',1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `setor`
--

DROP TABLE IF EXISTS `setor`;
CREATE TABLE `setor` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `titulo` varchar(50) NOT NULL,
    `descricao` varchar(250) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `setor` (`titulo`,`descricao`) VALUES
    ('Master','Setor com acesso a todas as Áreas e Funções.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissao`
--

DROP TABLE IF EXISTS `permissao`;
CREATE TABLE `permissao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idsetor` int(11) NOT NULL,
  `idpermissao` varchar(100) NOT NULL, -- Caracteres permitidos: "abcdefghijklmnopqrstuvxwyz_0123456789"
  `acesso` tinyint(1) NOT NULL DEFAULT '1',
  `tipo` varchar(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idsetor` (`idsetor`),
  KEY `idpermissao` (`idpermissao`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `permissao` (`id`, `idsetor`, `idpermissao`, `acesso`, `tipo`) VALUES
    (NULL, '1', 'seguranca-setor-cadastro', '1', 'zone'),
    (NULL, '1', 'seguranca-setor-consulta', '1', 'zone'),
    (NULL, '1', 'seguranca-setor-inserir', '1', 'func'),
    (NULL, '1', 'seguranca-setor-alterar', '1', 'func'),
    (NULL, '1', 'contato-telefone-operadora-cadastro', '1', 'zone'),
    (NULL, '1', 'contato-telefone-operadora-consulta', '1', 'zone'),
    (NULL, '1', 'contato-telefone-operadora-inserir', '1', 'func'), 
    (NULL, '1', 'contato-telefone-tipo-cadastro', '1', 'zone'),
    (NULL, '1', 'contato-telefone-tipo-consulta', '1', 'zone'),
    (NULL, '1', 'contato-telefone-tipo-inserir', '1', 'func'),
    (NULL, '1', 'sistema-usuario-cadastro', '1', 'zone'),
    (NULL, '1', 'sistema-usuario-consulta', '1', 'zone'),
    (NULL, '1', 'sistema-usuario-inserir', '1', 'func'),
    (NULL, '1', 'sistema-usuario-alterar', '1', 'func'),
    (NULL, '1', 'sistema-usuario-visualizar', '1', 'func'),
    (NULL, '1', 'ferramentas-xml', '1', 'zone');

-- --------------------------------------------------------

--
-- Estrutura da tabela `alocado`
--

DROP TABLE IF EXISTS `alocado`;
CREATE TABLE `alocado` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `iduser` int(11) NOT NULL,
    `idsetor` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `iduser` (`iduser`),
    KEY `idsetor` (`idsetor`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `alocado` (`id`, `iduser`, `idsetor`) VALUES 
    (NULL, '1', '1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
CREATE TABLE `pessoa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL, -- Razão Social
  `apelido` varchar(100) DEFAULT NULL, -- Nome Fantasia
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `iduser` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_iduser` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa_fisica`
--

DROP TABLE IF EXISTS `pessoa_fisica`;
CREATE TABLE IF NOT EXISTS `pessoa_fisica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpf` varchar(11) NOT NULL,
  `nascimento` date NOT NULL,
  `sexo` enum('masculino','feminino') NOT NULL,
  `nacionalidade` varchar(100) DEFAULT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `estado_civil` varchar(50) DEFAULT NULL,
  `conjuge` varchar(100) DEFAULT NULL,
  `cnpj` varchar(14) DEFAULT NULL, -- Inicio contato empresa
  `razao` varchar(100) NOT NULL,
  `telefone1` varchar(12) NOT NULL,
  `idoperadora1` int(11) NOT NULL DEFAULT '0',
  `telefone2` varchar(12) NOT NULL,
  `idoperadora2` int(11) NOT NULL DEFAULT '0',
  `telefone3` varchar(12) NOT NULL,
  `idoperadora3` int(11) NOT NULL DEFAULT '0',
  `cargo` varchar(50) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `numero` int(5) NOT NULL DEFAULT '0',
  `complemento` varchar(100) DEFAULT NULL, -- Fim contato empresa
  `idpessoa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_cpf` (`cpf`),
  UNIQUE KEY `un_idpessoa` (`idpessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ------------------------------------------------------

--
-- Estrutura da tabela `contato_cobranca`
--

DROP TABLE IF EXISTS `contato_cobranca`;
CREATE TABLE `contato_cobranca` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome` varchar(100) NOT NULL,
    `telefone` varchar(12) NOT NULL,
    `idoperadora` int(11) NOT NULL DEFAULT '0',
    `parentesco` varchar(50) DEFAULT NULL,
    `cep` varchar(8) DEFAULT NULL,
    `numero` int(5) NOT NULL DEFAULT '0',
    `complemento` varchar(100) DEFAULT NULL,
    `idpessoafisica` int(11) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ------------------------------------------------------

--
-- Estrutura da tabela `endereco_pessoa`
--

DROP TABLE IF EXISTS `endereco_pessoa`;
CREATE TABLE `endereco_pessoa` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `destinatario` varchar(100) NOT NULL, -- O recebedor da entrega
    `cep` varchar(8) DEFAULT NULL,
    `tipo` enum('residencial','comercial') NOT NULL,
    `numero` int(5) NOT NULL DEFAULT '0',
    `complemento` varchar(100) DEFAULT NULL,
    `referencia` varchar(100) DEFAULT NULL, -- Referencia para localização na entrega
    `principal` tinyint(1) NOT NULL DEFAULT '0', -- Define se o endereço é o prícipal para entregas
    `idpessoa` int(11) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ------------------------------------------------------

--
-- Estrutura da tabela `email_contato`
--

DROP TABLE IF EXISTS `email_contato`;
CREATE TABLE `email_contato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `idpessoa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ------------------------------------------------------

--
-- Estrutura da tabela `bairro`
--

DROP TABLE IF EXISTS `bairro`;
CREATE TABLE `bairro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uf` varchar(2) NOT NULL,
  `municipio` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `endereco`
--

DROP TABLE IF EXISTS `endereco`;
CREATE TABLE `endereco` (
  `cep` int(8) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `municipio` int(11) DEFAULT NULL,
  `bairro` int(11) DEFAULT NULL,
  `logradouro` int(11) DEFAULT NULL,
  `num_ini` int(11) DEFAULT '0',
  `num_fim` int(11) DEFAULT '0',
  `lado` tinyint(1) DEFAULT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`cep`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `estado`
--

DROP TABLE IF EXISTS `estado`;
CREATE TABLE `estado` (
  `uf` varchar(2) NOT NULL,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`uf`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `estado`
--

INSERT INTO `estado` (`uf`, `nome`) VALUES
('AC', 'Acre'),
('AL', 'Alagoas'),
('AP', 'Amapá'),
('AM', 'Amazonas'),
('BA', 'Bahia'),
('CE', 'Ceará'),
('DF', 'Distrito Federal'),
('ES', 'Espírito Ssnto'),
('GO', 'Goiás'),
('MA', 'Maranhão'),
('MT', 'Mato Grosso'),
('MS', 'Mato Grosso do Sul'),
('MG', 'Minas Gerais'),
('PA', 'Pará'),
('PB', 'Paraíba'),
('PR', 'Paraná'),
('PE', 'Pernambuco'),
('PI', 'Piauí'),
('RJ', 'Rio de Janeiro'),
('RN', 'Rio Grande do Norte'),
('RS', 'Rio Grande do Sul'),
('RO', 'Rondonia'),
('RR', 'Roraima'),
('SC', 'Santa Catarina'),
('SP', 'São Paulo'),
('SE', 'Sergipe'),
('TO', 'Tocantins');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionario`
--

-- DROP TABLE IF EXISTS `funcionario`;
-- CREATE TABLE IF NOT EXISTS `funcionario` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `rg` varchar(10) NOT NULL,
--   `cargo` int(11) NOT NULL,
--   `salario` float NOT NULL DEFAULT '0',
--   `pessoa_fisica` int(11) NOT NULL,
--   `ferias` tinyint(1) NOT NULL DEFAULT '0',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `un_rg` (`rg`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logradouro`
--

DROP TABLE IF EXISTS `logradouro`;
CREATE TABLE `logradouro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uf` varchar(2) NOT NULL,
  `municipio` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `municipio`
--

DROP TABLE IF EXISTS `municipio`;
CREATE TABLE `municipio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uf` varchar(2) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `operadora_telefone`
--

DROP TABLE IF EXISTS `operadora_telefone`;
CREATE TABLE `operadora_telefone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operadora` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_operadora` (`operadora`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `operadora_telefone`
--

INSERT INTO `operadora_telefone` (`id`, `operadora`) VALUES
(NULL, 'Claro'),
(NULL, 'Oi'),
(NULL, 'Tim'),
(NULL, 'Vivo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `telefone`
--

DROP TABLE IF EXISTS `telefone`;
CREATE TABLE `telefone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telefone` varchar(12) NOT NULL,
  `idtipo` int(11) NOT NULL,
  `idoperadora` int(11) NOT NULL,
  `idpessoa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_telefone`
--

DROP TABLE IF EXISTS `tipo_telefone`;
CREATE TABLE IF NOT EXISTS `tipo_telefone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_tipo` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tipo_telefone`
--

INSERT INTO `tipo_telefone` (`id`, `tipo`) VALUES
(NULL, 'Principal'),
(NULL, 'Casa'),
(NULL, 'Celular'),
(NULL, 'Fax'),
(NULL, 'Trabalho'),
(NULL, 'WhatsApp');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
