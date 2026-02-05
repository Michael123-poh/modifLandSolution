-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 08:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fodjomanage`
--

-- --------------------------------------------------------

--
-- Table structure for table `acheteur`
--

CREATE TABLE `acheteur` (
  `idAcheteur` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `nomAcheteur` varchar(50) NOT NULL,
  `adresseAcheteur` varchar(100) DEFAULT NULL,
  `telephoneAcheteur` varchar(10) NOT NULL,
  `numeroCNI` varchar(50) NOT NULL,
  `dateNaisAcheteur` date NOT NULL,
  `nomCommercial` varchar(50) NOT NULL,
  `notesAcheteur` text DEFAULT NULL,
  `dateCreateAcheteur` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acheteur`
--

INSERT INTO `acheteur` (`idAcheteur`, `idEmploye`, `nomAcheteur`, `adresseAcheteur`, `telephoneAcheteur`, `numeroCNI`, `dateNaisAcheteur`, `nomCommercial`, `notesAcheteur`, `dateCreateAcheteur`) VALUES
('ACH00001', 'EMP00001', 'lorent', 'Douala, yassa', '671234561', 'LTS098B43KV34', '1993-09-06', 'yves', '  ', '2025-07-23 14:30:27'),
('ACH00002', 'EMP00001', 'flaure', 'Bonamoussadi', '674328017', 'LTS018B43MT00', '2004-03-10', 'yves', NULL, '2025-07-23 14:33:31'),
('ACH00003', 'EMP00001', 'FORTUNE NYAMI ', 'viuihihihoih', '678901212', '123456789', '2025-07-31', 'yves Mick', 'diuwqbiudb xkzkjnkjdniendm', '2025-08-08 09:26:38'),
('ACH00004', 'EMP00001', 'TITO NINA EDGAR', 'ndongbong', '678901234', '123456789012', '1996-12-20', 'yves Mick', 'pv express a rendre avant le 20-08-2025  ', '2025-08-14 10:33:53'),
('ACH00005', 'EMP00001', 'TOTO NANI', 'ndongbong', '678901234', '123456789666', '2013-12-20', 'yves Mick', 'pv express', '2025-08-07 15:09:56'),
('ACH00006', 'EMP00001', 'YVES MAILLORE', 'yassa', '678901234', '123456789012', '1991-02-28', 'yves Mick', 'raas', '2025-08-07 15:28:56'),
('ACH00007', 'EMP00001', 'TABA JEAN', 'Bonamoussadi', '678901256', '214365879', '1986-10-23', 'yves Mick', 'ras', '2025-08-08 10:56:38'),
('ACH00008', 'EMP00001', 'FODJO CARLOS', 'PK13', '694340165', '694340165', '1974-04-09', 'yves Mick', 'ras', '2025-08-08 10:59:27');

-- --------------------------------------------------------

--
-- Table structure for table `blocs`
--

CREATE TABLE `blocs` (
  `idBloc` varchar(10) NOT NULL,
  `numeroTitreFoncier` varchar(100) NOT NULL,
  `nomBloc` varchar(50) NOT NULL,
  `superficieinitialBloc` int(100) NOT NULL,
  `superficieCourranteBloc` int(100) NOT NULL,
  `statutBloc` int(10) NOT NULL DEFAULT 0,
  `dateCreateBloc` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blocs`
--

INSERT INTO `blocs` (`idBloc`, `numeroTitreFoncier`, `nomBloc`, `superficieinitialBloc`, `superficieCourranteBloc`, `statutBloc`, `dateCreateBloc`) VALUES
('BLC00001', 'TF-3955/SM', 'Bloc A1', 5000, 3200, 0, '2025-07-14 14:24:20'),
('BLC00002', 'TF-3955/SM', 'Bloc A2', 3000, 3000, 0, '2025-07-14 14:24:20'),
('BLC00003', 'TF-7768/SM', 'Bloc B1', 15000, 15000, 0, '2025-07-14 14:27:11'),
('BLC00004', 'TF-7768/SM', 'Bloc B2', 2000, 1500, 0, '2025-07-14 14:27:11'),
('BLC00005', 'TF-5577/SM', 'Bloc C1', 5000, 4400, 0, '2025-07-22 22:28:03'),
('BLC00006', 'bekoko', 'bloc1', 1000, 1000, 0, '2025-08-07 15:06:22');

-- --------------------------------------------------------

--
-- Table structure for table `clientsdescente`
--

CREATE TABLE `clientsdescente` (
  `idClient` varchar(10) NOT NULL,
  `idProspect` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `nomClient` varchar(50) NOT NULL,
  `telephoneClient` varchar(10) NOT NULL,
  `montantDescente` int(100) NOT NULL,
  `appreciationClient` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `descentes`
--

CREATE TABLE `descentes` (
  `idDescente` varchar(10) NOT NULL,
  `nbrePersonnePresente` int(100) NOT NULL,
  `dateDescente` date NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `dateCreateDescente` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dossiers`
--

CREATE TABLE `dossiers` (
  `idDossier` varchar(10) NOT NULL,
  `numeroProcesVerbal` varchar(100) NOT NULL,
  `numeroDossierTech` varchar(100) NOT NULL,
  `numeroDocAcquisition` varchar(100) NOT NULL,
  `dateProcesVerbal` date NOT NULL,
  `dateDossierTech` date NOT NULL,
  `dateDocAcquisition` date NOT NULL,
  `dateCreateDossier` date NOT NULL,
  `dateMiseAJour` date NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `idAcheteur` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dossiers`
--

INSERT INTO `dossiers` (`idDossier`, `numeroProcesVerbal`, `numeroDossierTech`, `numeroDocAcquisition`, `dateProcesVerbal`, `dateDossierTech`, `dateDocAcquisition`, `dateCreateDossier`, `dateMiseAJour`, `idEmploye`, `idAcheteur`) VALUES
('DOS00001', 'PV00001', 'DT00001', '', '2025-07-25', '2025-07-25', '2025-07-25', '2025-07-23', '2025-07-25', 'EMP00001', 'ACH00001'),
('DOS00002', 'PV00002', 'DT00002', 'DA00001', '2025-07-28', '2025-07-28', '2025-08-08', '2025-07-23', '2025-08-08', 'EMP00001', 'ACH00002'),
('DOS00003', 'PV00004', 'DT00003', '', '2025-08-08', '2025-08-08', '0000-00-00', '2025-08-01', '2025-08-08', 'EMP00001', 'ACH00003'),
('DOS00004', 'PV00003', '', '', '2025-08-08', '0000-00-00', '0000-00-00', '2025-08-01', '2025-08-08', 'EMP00001', 'ACH00004'),
('DOS00005', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', '2025-08-05', '0000-00-00', 'EMP00001', 'ACH00005'),
('DOS00006', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', '2025-08-06', '0000-00-00', 'EMP00001', 'ACH00006'),
('DOS00007', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', '2025-08-08', '0000-00-00', 'EMP00001', 'ACH00007'),
('DOS00008', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', '2025-08-08', '0000-00-00', 'EMP00001', 'ACH00008');

-- --------------------------------------------------------

--
-- Table structure for table `employe`
--

CREATE TABLE `employe` (
  `idEmploye` varchar(10) NOT NULL,
  `idTypeEmploye` varchar(10) NOT NULL,
  `nomEmploye` varchar(50) NOT NULL,
  `pseudoEmploye` varchar(50) NOT NULL,
  `emailEmploye` varchar(255) NOT NULL,
  `adresseEmploye` varchar(250) NOT NULL,
  `dateNaisEmploye` date NOT NULL,
  `telephoneEmploye` varchar(10) NOT NULL,
  `mdpEmploye` varchar(250) NOT NULL,
  `dateCreateEmploye` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employe`
--

INSERT INTO `employe` (`idEmploye`, `idTypeEmploye`, `nomEmploye`, `pseudoEmploye`, `emailEmploye`, `adresseEmploye`, `dateNaisEmploye`, `telephoneEmploye`, `mdpEmploye`, `dateCreateEmploye`) VALUES
('EMP00001', 'TPE00001', 'yves Mick', 'Micky', '', 'Douala, Cameroun', '2015-07-09', '678909832', '12345', '2025-07-14 13:41:43'),
('EMP00002', 'TPE00001', 'NYAMI HILARY', 'hilary', 'hilary@gmail.com', 'douala, bonanjo', '1970-03-30', '694346262', '$2y$10$WvO/56rFft8jfkRinujtsOtwAazSWEZ1CnLgVQC4NqtQHYKBYZdau', '2025-08-11 15:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `employesprivileges`
--

CREATE TABLE `employesprivileges` (
  `idEmp_Pri` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `idPrivilege` varchar(10) NOT NULL,
  `dateCreateEmployePrivilege` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employesprivileges`
--

INSERT INTO `employesprivileges` (`idEmp_Pri`, `idEmploye`, `idPrivilege`, `dateCreateEmployePrivilege`) VALUES
('EPP00001', 'EMP00001', 'PRI00001', '2025-07-30 09:46:23'),
('EPP00002', 'EMP00001', 'PRI00002', '2025-07-30 09:46:23'),
('EPP00003', 'EMP00001', 'PRI00003', '2025-07-30 09:46:23'),
('EPP00004', 'EMP00001', 'PRI00004', '2025-07-30 09:46:23'),
('EPP00005', 'EMP00001', 'PRI00005', '2025-07-30 09:46:23'),
('EPP00006', 'EMP00001', 'PRI00006', '2025-07-30 09:46:23'),
('EPP00007', 'EMP00001', 'PRI00007', '2025-07-30 09:46:23'),
('EPP00008', 'EMP00001', 'PRI00008', '2025-07-30 09:46:23'),
('EPP00009', 'EMP00001', 'PRI00009', '2025-07-30 09:46:23'),
('EPP00010', 'EMP00001', 'PRI00010', '2025-07-30 09:46:23'),
('EPP00011', 'EMP00001', 'PRI00011', '2025-07-30 09:46:23'),
('EPP00012', 'EMP00001', 'PRI00012', '2025-07-30 09:46:23'),
('EPP00013', 'EMP00001', 'PRI00013', '2025-07-30 09:46:23'),
('EPP00014', 'EMP00001', 'PRI00014', '2025-07-30 09:46:23'),
('EPP00015', 'EMP00001', 'PRI00015', '2025-07-30 09:46:23');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `idPrivilege` varchar(10) NOT NULL,
  `libellePrivilege` varchar(150) NOT NULL,
  `dateCreatePrivilege` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`idPrivilege`, `libellePrivilege`, `dateCreatePrivilege`) VALUES
('PRI00001', 'Ajouter un employé', '2025-07-30 09:46:23'),
('PRI00002', 'Enregistrer un prospect', '2025-07-30 09:46:23'),
('PRI00003', 'Enregistrer un Client', '2025-07-30 09:46:23'),
('PRI00004', 'Enregistrer un Acheteur', '2025-07-30 09:46:23'),
('PRI00005', 'Consulter le dashboard', '2025-07-30 09:46:23'),
('PRI00006', 'Ajouter un site', '2025-07-30 09:46:23'),
('PRI00007', 'Modifier un site', '2025-07-30 09:46:23'),
('PRI00008', 'Ajouter une seletion', '2025-07-30 09:46:23'),
('PRI00009', 'Enregistrer un versement', '2025-07-30 09:46:23'),
('PRI00010', 'Modifier un versment', '2025-07-30 09:46:23'),
('PRI00011', 'Creer un Dossier', '2025-07-30 09:46:23'),
('PRI00012', 'Modifier un Dossier', '2025-07-30 09:46:23'),
('PRI00013', 'Programmer une descente', '2025-07-30 09:46:23'),
('PRI00014', 'Deprogrammer une descente', '2025-07-30 09:46:23'),
('PRI00015', 'Modifier une descente', '2025-07-30 09:46:23');

-- --------------------------------------------------------

--
-- Table structure for table `prospects`
--

CREATE TABLE `prospects` (
  `idProspect` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `nomProspect` varchar(50) NOT NULL,
  `telephoneProspect` varchar(10) NOT NULL,
  `lieuProspection` varchar(50) NOT NULL,
  `dateCreateProspect` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recasement`
--

CREATE TABLE `recasement` (
  `id_recasement` varchar(10) NOT NULL,
  `superficie` int(100) NOT NULL,
  `montantTotalVerser` int(100) NOT NULL,
  `selection` varchar(100) NOT NULL,
  `date_create` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selection`
--

CREATE TABLE `selection` (
  `idSelection` varchar(10) NOT NULL,
  `superficieSelection` double NOT NULL,
  `lot` varchar(50) NOT NULL,
  `montantParMetre` double NOT NULL,
  `montantTotalSelection` double NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `idBloc` varchar(10) NOT NULL,
  `idAcheteur` varchar(10) NOT NULL,
  `dateCreateSelection` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `selection`
--

INSERT INTO `selection` (`idSelection`, `superficieSelection`, `lot`, `montantParMetre`, `montantTotalSelection`, `idEmploye`, `idBloc`, `idAcheteur`, `dateCreateSelection`) VALUES
('SEL00001', 300, 'lot 9p', 8500, 2550000, 'EMP00001', 'BLC00001', 'ACH00001', '2025-07-23 14:51:10'),
('SEL00002', 1000, 'lot 7, lot 8, lot 9p', 6500, 6500000, 'EMP00001', 'BLC00002', 'ACH00002', '2025-07-23 14:51:10'),
('SEL00003', 500, 'TF-3955/SM BLC00001', 9000, 4500000, 'EMP00001', 'BLC00001', 'ACH00003', '2025-08-08 09:26:38'),
('SEL00004', 700, 'TF-5577/SM BLC00005', 8000, 5600000, 'EMP00001', 'BLC00005', 'ACH00004', '2025-08-14 10:33:53'),
('SEL00005', 500, 'TF-5577/SM BLC00005', 9000, 4500000, 'EMP00001', 'BLC00005', 'ACH00005', '2025-08-07 15:09:56'),
('SEL00006', 500, 'TF-7768/SM BLC00004', 8500, 4250000, 'EMP00001', 'BLC00004', 'ACH00006', '2025-08-07 15:28:56'),
('SEL00007', 500, 'bekoko BLC00001', 10000, 5000000, 'EMP00001', 'BLC00001', 'ACH00007', '2025-08-08 10:56:38'),
('SEL00008', 200, 'bekoko BLC00001', 10000, 2000000, 'EMP00001', 'BLC00001', 'ACH00008', '2025-08-08 10:59:27');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `numeroTitreFoncier` varchar(100) NOT NULL,
  `localisationSite` varchar(100) NOT NULL,
  `superficieInitialeSite` int(100) NOT NULL,
  `superficieCourranteSite` int(100) NOT NULL,
  `prix_Vente` int(11) NOT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT 0,
  `dateCreateSite` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`numeroTitreFoncier`, `localisationSite`, `superficieInitialeSite`, `superficieCourranteSite`, `prix_Vente`, `statut`, `dateCreateSite`) VALUES
('bekoko', 'bonaberi-bekoko', 9000, 9000, 9500, 0, '2025-08-07'),
('bonamoussadi', 'parcours-vita', 1200, 1200, 150000, 0, '2025-08-08'),
('TF-3955/SM', 'Dibamba, Kondjock', 50000, 47200, 8500, 0, '2025-07-14'),
('TF-5577/SM', 'Dibamba, pitie garre', 10000, 6900, 6500, 0, '2025-07-22'),
('TF-7768/SM', 'Dibamba, Kendeck', 100000, 98500, 5000, 1, '2025-07-07');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `idTransaction` varchar(10) NOT NULL,
  `montantTransaction` double NOT NULL,
  `dateTransaction` date NOT NULL,
  `dateCreateTransaction` datetime NOT NULL,
  `idVersement` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`idTransaction`, `montantTransaction`, `dateTransaction`, `dateCreateTransaction`, `idVersement`, `idEmploye`) VALUES
('TSN00001', 9000000, '2025-08-08', '2025-08-08 09:26:38', 'VER00001', 'EMP00001'),
('TSN00002', 900000, '2025-08-14', '2025-08-14 10:33:53', 'VER00002', 'EMP00001'),
('TSN00003', 500000, '2025-08-07', '2025-08-07 15:09:56', 'VER00005', 'EMP00001'),
('TSN00004', 100000, '2025-08-07', '2025-08-07 15:28:56', 'VER00006', 'EMP00001'),
('TSN00005', 5000000, '2025-08-08', '2025-08-08 10:56:38', 'VER00007', 'EMP00001'),
('TSN00006', 500000, '2025-08-08', '2025-08-08 10:59:27', 'VER00008', 'EMP00001');

-- --------------------------------------------------------

--
-- Table structure for table `typeemploye`
--

CREATE TABLE `typeemploye` (
  `idTypeEmploye` varchar(10) NOT NULL,
  `libelleFonction` text NOT NULL,
  `dateCreateTypeEmploye` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typeemploye`
--

INSERT INTO `typeemploye` (`idTypeEmploye`, `libelleFonction`, `dateCreateTypeEmploye`) VALUES
('TPE00001', 'INFOMATICIEN', '2025-07-14 13:41:16');

-- --------------------------------------------------------

--
-- Table structure for table `versements`
--

CREATE TABLE `versements` (
  `idVersement` varchar(10) NOT NULL,
  `fraisOuvertureDossier` double NOT NULL,
  `premiereTranche` double NOT NULL,
  `montantVersement` double NOT NULL,
  `dateVersement` date NOT NULL,
  `dateCreateVersement` datetime NOT NULL,
  `idSelection` varchar(10) NOT NULL,
  `idAcheteur` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `versements`
--

INSERT INTO `versements` (`idVersement`, `fraisOuvertureDossier`, `premiereTranche`, `montantVersement`, `dateVersement`, `dateCreateVersement`, `idSelection`, `idAcheteur`) VALUES
('VER00001', 9000000, 0, 9000000, '2025-08-08', '2025-08-08 09:26:38', 'SEL00003', 'ACH00003'),
('VER00002', 8000000, 0, 900000, '2025-08-14', '2025-08-14 10:33:53', 'SEL00004', 'ACH00004'),
('VER00003', 8000000, 0, 8000000, '2025-08-05', '2025-08-01 14:15:46', 'SEL00001', 'ACH00001'),
('VER00004', 8000000, 0, 8000000, '2025-08-05', '2025-08-01 14:15:46', 'SEL00002', 'ACH00002'),
('VER00005', 500000, 0, 500000, '2025-08-07', '2025-08-07 15:09:56', 'SEL00005', 'ACH00005'),
('VER00006', 100000, 0, 100000, '2025-08-07', '2025-08-07 15:28:56', 'SEL00006', 'ACH00006'),
('VER00007', 5000000, 0, 5000000, '2025-08-08', '2025-08-08 10:56:38', 'SEL00007', 'ACH00007'),
('VER00008', 500000, 0, 500000, '2025-08-08', '2025-08-08 10:59:27', 'SEL00008', 'ACH00008');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acheteur`
--
ALTER TABLE `acheteur`
  ADD PRIMARY KEY (`idAcheteur`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Indexes for table `blocs`
--
ALTER TABLE `blocs`
  ADD PRIMARY KEY (`idBloc`),
  ADD KEY `numeroTitreFoncier` (`numeroTitreFoncier`);

--
-- Indexes for table `clientsdescente`
--
ALTER TABLE `clientsdescente`
  ADD PRIMARY KEY (`idClient`),
  ADD KEY `idProspect` (`idProspect`,`idEmploye`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Indexes for table `descentes`
--
ALTER TABLE `descentes`
  ADD PRIMARY KEY (`idDescente`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Indexes for table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`idDossier`),
  ADD KEY `idEmploye` (`idEmploye`,`idAcheteur`),
  ADD KEY `idAcheteur` (`idAcheteur`);

--
-- Indexes for table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`idEmploye`),
  ADD UNIQUE KEY `emailEmploye` (`emailEmploye`),
  ADD KEY `idTypeEmploye` (`idTypeEmploye`);

--
-- Indexes for table `employesprivileges`
--
ALTER TABLE `employesprivileges`
  ADD PRIMARY KEY (`idEmp_Pri`),
  ADD KEY `idEmploye` (`idEmploye`,`idPrivilege`),
  ADD KEY `idPrivilege` (`idPrivilege`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`idPrivilege`);

--
-- Indexes for table `prospects`
--
ALTER TABLE `prospects`
  ADD PRIMARY KEY (`idProspect`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Indexes for table `recasement`
--
ALTER TABLE `recasement`
  ADD PRIMARY KEY (`id_recasement`);

--
-- Indexes for table `selection`
--
ALTER TABLE `selection`
  ADD PRIMARY KEY (`idSelection`),
  ADD KEY `idEmploye` (`idEmploye`),
  ADD KEY `idBloc` (`idBloc`),
  ADD KEY `idAcheteur` (`idAcheteur`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`numeroTitreFoncier`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`idTransaction`),
  ADD KEY `idVersement` (`idVersement`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Indexes for table `typeemploye`
--
ALTER TABLE `typeemploye`
  ADD PRIMARY KEY (`idTypeEmploye`);

--
-- Indexes for table `versements`
--
ALTER TABLE `versements`
  ADD PRIMARY KEY (`idVersement`),
  ADD KEY `idSelection` (`idSelection`,`idAcheteur`),
  ADD KEY `idAcheteur` (`idAcheteur`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acheteur`
--
ALTER TABLE `acheteur`
  ADD CONSTRAINT `acheteur_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Constraints for table `blocs`
--
ALTER TABLE `blocs`
  ADD CONSTRAINT `blocs_ibfk_1` FOREIGN KEY (`numeroTitreFoncier`) REFERENCES `sites` (`numeroTitreFoncier`);

--
-- Constraints for table `clientsdescente`
--
ALTER TABLE `clientsdescente`
  ADD CONSTRAINT `clientsdescente_ibfk_1` FOREIGN KEY (`idProspect`) REFERENCES `prospects` (`idProspect`),
  ADD CONSTRAINT `clientsdescente_ibfk_2` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Constraints for table `descentes`
--
ALTER TABLE `descentes`
  ADD CONSTRAINT `descentes_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Constraints for table `dossiers`
--
ALTER TABLE `dossiers`
  ADD CONSTRAINT `dossiers_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `dossiers_ibfk_2` FOREIGN KEY (`idAcheteur`) REFERENCES `acheteur` (`idAcheteur`);

--
-- Constraints for table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`idTypeEmploye`) REFERENCES `typeemploye` (`idTypeEmploye`);

--
-- Constraints for table `employesprivileges`
--
ALTER TABLE `employesprivileges`
  ADD CONSTRAINT `employesprivileges_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `employesprivileges_ibfk_2` FOREIGN KEY (`idPrivilege`) REFERENCES `privileges` (`idPrivilege`);

--
-- Constraints for table `prospects`
--
ALTER TABLE `prospects`
  ADD CONSTRAINT `prospects_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Constraints for table `selection`
--
ALTER TABLE `selection`
  ADD CONSTRAINT `selection_ibfk_1` FOREIGN KEY (`idAcheteur`) REFERENCES `acheteur` (`idAcheteur`),
  ADD CONSTRAINT `selection_ibfk_2` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `selection_ibfk_3` FOREIGN KEY (`idBloc`) REFERENCES `blocs` (`idBloc`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`idVersement`) REFERENCES `versements` (`idVersement`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Constraints for table `versements`
--
ALTER TABLE `versements`
  ADD CONSTRAINT `versements_ibfk_1` FOREIGN KEY (`idSelection`) REFERENCES `selection` (`idSelection`),
  ADD CONSTRAINT `versements_ibfk_2` FOREIGN KEY (`idAcheteur`) REFERENCES `acheteur` (`idAcheteur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
