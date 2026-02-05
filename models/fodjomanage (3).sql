-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 28 juil. 2025 à 15:00
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `fodjomanage`
--

-- --------------------------------------------------------

--
-- Structure de la table `acheteur`
--

CREATE TABLE `acheteur` (
  `idAcheteur` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `nomAcheteur` varchar(50) NOT NULL,
  `adresseAcheteur` varchar(100) NOT NULL,
  `telephoneAcheteur` varchar(10) NOT NULL,
  `numeroCNI` varchar(50) NOT NULL,
  `dateNaisAcheteur` date NOT NULL,
  `nomCommercial` varchar(50) NOT NULL,
  `dateCreateAcheteur` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `acheteur`
--

INSERT INTO `acheteur` (`idAcheteur`, `idEmploye`, `nomAcheteur`, `adresseAcheteur`, `telephoneAcheteur`, `numeroCNI`, `dateNaisAcheteur`, `nomCommercial`, `dateCreateAcheteur`) VALUES
('ACH00001', 'EMP00001', 'lorent', 'Douala, yassa', '671234561', 'LTS098B43KV34', '1993-09-06', 'yves', '2025-07-23 14:30:27'),
('ACH00002', 'EMP00001', 'flaure', 'Bonamoussadi', '674328017', 'LTS018B43MT00', '2004-03-10', 'yves', '2025-07-23 14:33:31');

-- --------------------------------------------------------

--
-- Structure de la table `blocs`
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
-- Déchargement des données de la table `blocs`
--

INSERT INTO `blocs` (`idBloc`, `numeroTitreFoncier`, `nomBloc`, `superficieinitialBloc`, `superficieCourranteBloc`, `statutBloc`, `dateCreateBloc`) VALUES
('BLC00001', 'TF-3955/SM', 'Bloc A1', 5000, 5000, 0, '2025-07-14 14:24:20'),
('BLC00002', 'TF-3955/SM', 'Bloc A2', 3000, 3000, 0, '2025-07-14 14:24:20'),
('BLC00003', 'TF-7768/SM', 'Bloc B1', 15000, 15000, 0, '2025-07-14 14:27:11'),
('BLC00004', 'TF-7768/SM', 'Bloc B2', 2000, 2000, 0, '2025-07-14 14:27:11'),
('BLC00005', 'TF-5577/SM', 'Bloc C1', 5000, 5000, 0, '2025-07-22 22:28:03');

-- --------------------------------------------------------

--
-- Structure de la table `clientsdescente`
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
-- Structure de la table `descentes`
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
-- Structure de la table `dossiers`
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
-- Déchargement des données de la table `dossiers`
--

INSERT INTO `dossiers` (`idDossier`, `numeroProcesVerbal`, `numeroDossierTech`, `numeroDocAcquisition`, `dateProcesVerbal`, `dateDossierTech`, `dateDocAcquisition`, `dateCreateDossier`, `dateMiseAJour`, `idEmploye`, `idAcheteur`) VALUES
('DOS00001', 'PV00001', 'DT00001', '', '2025-07-25', '2025-07-25', '2025-07-25', '2025-07-23', '2025-07-25', 'EMP00001', 'ACH00001'),
('DOS00002', 'PV00002', 'DT00002', '', '2025-07-28', '2025-07-28', '2025-07-26', '2025-07-23', '2025-07-28', 'EMP00001', 'ACH00002');

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `idEmploye` varchar(10) NOT NULL,
  `idTypeEmploye` varchar(10) NOT NULL,
  `nomEmploye` varchar(50) NOT NULL,
  `pseudoEmploye` varchar(50) NOT NULL,
  `adresseEmploye` varchar(250) NOT NULL,
  `dateNaisEmploye` date NOT NULL,
  `telephoneEmploye` varchar(10) NOT NULL,
  `mdpEmploye` varchar(250) NOT NULL,
  `dateCreateEmploye` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `employe`
--



-- --------------------------------------------------------
INSERT INTO `employe` (`idEmploye`, `idTypeEmploye`, `nomEmploye`, `pseudoEmploye`, `adresseEmploye`, `dateNaisEmploye`, `telephoneEmploye`, `mdpEmploye`, `dateCreateEmploye`) VALUES
('EMP00001', 'TPE00001', 'yves Mick', 'Micky', 'Douala, Cameroun', '2015-07-09', '678909832', '12345', '2025-07-14 13:41:43');
--
-- Structure de la table `employesprivileges`
--

CREATE TABLE `employesprivileges` (
  `idEmp_Pri` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL,
  `idPrivilege` varchar(10) NOT NULL,
  `dateCreateEmployePrivilege` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `privileges`
--

CREATE TABLE `privileges` (
  `idPrivilege` varchar(10) NOT NULL,
  `libellePrivilege` varchar(150) NOT NULL,
  `dateCreatePrivilege` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
INSERT INTO `privileges`(`idPrivilege`, `libellePrivilege`, `dateCreatePrivilege`)
VALUES ('PRI00001', 'Ajouter un employé', NOW()),
        ('PRI00002', 'Enregistrer un prospect', NOW()),
        ('PRI00003', 'Enregistrer un Client', NOW()),
        ('PRI00004', 'Enregistrer un Acheteur', NOW()),
        ('PRI00005', 'Consulter le dashboard', NOW()),
        ('PRI00006', 'Ajouter un site', NOW()),
        ('PRI00007', 'Modifier un site', NOW()),
        ('PRI00008', 'Ajouter une seletion', NOW()),
        ('PRI00009', 'Enregistrer un versement', NOW()),
        ('PRI00010', 'Modifier un versment', NOW()),
        ('PRI00011', 'Creer un Dossier', NOW()),
        ('PRI00012', 'Modifier un Dossier', NOW()),
        ('PRI00013', 'Programmer une descente', NOW()),
        ('PRI00014', 'Deprogrammer une descente', NOW()),
        ('PRI00015', 'Modifier une descente', NOW());
--
-- Structure de la table `prospects`
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
-- Structure de la table `recasement`
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
-- Structure de la table `selection`
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
-- Déchargement des données de la table `selection`
--

INSERT INTO `selection` (`idSelection`, `superficieSelection`, `lot`, `montantParMetre`, `montantTotalSelection`, `idEmploye`, `idBloc`, `idAcheteur`, `dateCreateSelection`) VALUES
('SEL00001', 300, 'lot 9p', 8500, 2550000, 'EMP00001', 'BLC00001', 'ACH00001', '2025-07-23 14:51:10'),
('SEL00002', 1000, 'lot 7, lot 8, lot 9p', 6500, 6500000, 'EMP00001', 'BLC00002', 'ACH00002', '2025-07-23 14:51:10');

-- --------------------------------------------------------

--
-- Structure de la table `sites`
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
-- Déchargement des données de la table `sites`
--

INSERT INTO `sites` (`numeroTitreFoncier`, `localisationSite`, `superficieInitialeSite`, `superficieCourranteSite`, `prix_Vente`, `statut`, `dateCreateSite`) VALUES
('TF-3955/SM', 'Dibamba, Kondjock', 50000, 50000, 8500, 0, '2025-07-14'),
('TF-5577/SM', 'Dibamba, pitie garre', 10000, 10000, 6500, 0, '2025-07-22'),
('TF-7768/SM', 'Dibamba, Kendeck', 100000, 100000, 5000, 1, '2025-07-07');

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `idTransaction` varchar(10) NOT NULL,
  `montantTransaction` double NOT NULL,
  `dateTransaction` date NOT NULL,
  `dateCreateTransaction` datetime NOT NULL,
  `idVersement` varchar(10) NOT NULL,
  `idEmploye` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typeemploye`
--

CREATE TABLE `typeemploye` (
  `idTypeEmploye` varchar(10) NOT NULL,
  `libelleFonction` text NOT NULL,
  `dateCreateTypeEmploye` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `typeemploye`
--

INSERT INTO `typeemploye` (`idTypeEmploye`, `libelleFonction`, `dateCreateTypeEmploye`) VALUES
('TPE00001', 'INFOMATICIEN', '2025-07-14 13:41:16');

-- --------------------------------------------------------

--
-- Structure de la table `versements`
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
-- Index pour les tables déchargées
--

--
-- Index pour la table `acheteur`
--
ALTER TABLE `acheteur`
  ADD PRIMARY KEY (`idAcheteur`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Index pour la table `blocs`
--
ALTER TABLE `blocs`
  ADD PRIMARY KEY (`idBloc`),
  ADD KEY `numeroTitreFoncier` (`numeroTitreFoncier`);

--
-- Index pour la table `clientsdescente`
--
ALTER TABLE `clientsdescente`
  ADD PRIMARY KEY (`idClient`),
  ADD KEY `idProspect` (`idProspect`,`idEmploye`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Index pour la table `descentes`
--
ALTER TABLE `descentes`
  ADD PRIMARY KEY (`idDescente`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Index pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`idDossier`),
  ADD KEY `idEmploye` (`idEmploye`,`idAcheteur`),
  ADD KEY `idAcheteur` (`idAcheteur`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`idEmploye`),
  ADD KEY `idTypeEmploye` (`idTypeEmploye`);

--
-- Index pour la table `employesprivileges`
--
ALTER TABLE `employesprivileges`
  ADD PRIMARY KEY (`idEmp_Pri`),
  ADD KEY `idEmploye` (`idEmploye`,`idPrivilege`),
  ADD KEY `idPrivilege` (`idPrivilege`);

--
-- Index pour la table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`idPrivilege`);

--
-- Index pour la table `prospects`
--
ALTER TABLE `prospects`
  ADD PRIMARY KEY (`idProspect`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Index pour la table `recasement`
--
ALTER TABLE `recasement`
  ADD PRIMARY KEY (`id_recasement`);

--
-- Index pour la table `selection`
--
ALTER TABLE `selection`
  ADD PRIMARY KEY (`idSelection`),
  ADD KEY `idEmploye` (`idEmploye`),
  ADD KEY `idBloc` (`idBloc`),
  ADD KEY `idAcheteur` (`idAcheteur`);

--
-- Index pour la table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`numeroTitreFoncier`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`idTransaction`),
  ADD KEY `idVersement` (`idVersement`),
  ADD KEY `idEmploye` (`idEmploye`);

--
-- Index pour la table `typeemploye`
--
ALTER TABLE `typeemploye`
  ADD PRIMARY KEY (`idTypeEmploye`);

--
-- Index pour la table `versements`
--
ALTER TABLE `versements`
  ADD PRIMARY KEY (`idVersement`),
  ADD KEY `idSelection` (`idSelection`,`idAcheteur`),
  ADD KEY `idAcheteur` (`idAcheteur`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `acheteur`
--
ALTER TABLE `acheteur`
  ADD CONSTRAINT `acheteur_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Contraintes pour la table `blocs`
--
ALTER TABLE `blocs`
  ADD CONSTRAINT `blocs_ibfk_1` FOREIGN KEY (`numeroTitreFoncier`) REFERENCES `sites` (`numeroTitreFoncier`);

--
-- Contraintes pour la table `clientsdescente`
--
ALTER TABLE `clientsdescente`
  ADD CONSTRAINT `clientsdescente_ibfk_1` FOREIGN KEY (`idProspect`) REFERENCES `prospects` (`idProspect`),
  ADD CONSTRAINT `clientsdescente_ibfk_2` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Contraintes pour la table `descentes`
--
ALTER TABLE `descentes`
  ADD CONSTRAINT `descentes_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Contraintes pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD CONSTRAINT `dossiers_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `dossiers_ibfk_2` FOREIGN KEY (`idAcheteur`) REFERENCES `acheteur` (`idAcheteur`);

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`idTypeEmploye`) REFERENCES `typeemploye` (`idTypeEmploye`);

--
-- Contraintes pour la table `employesprivileges`
--
ALTER TABLE `employesprivileges`
  ADD CONSTRAINT `employesprivileges_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `employesprivileges_ibfk_2` FOREIGN KEY (`idPrivilege`) REFERENCES `privileges` (`idPrivilege`);

--
-- Contraintes pour la table `prospects`
--
ALTER TABLE `prospects`
  ADD CONSTRAINT `prospects_ibfk_1` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Contraintes pour la table `selection`
--
ALTER TABLE `selection`
  ADD CONSTRAINT `selection_ibfk_1` FOREIGN KEY (`idAcheteur`) REFERENCES `acheteur` (`idAcheteur`),
  ADD CONSTRAINT `selection_ibfk_2` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `selection_ibfk_3` FOREIGN KEY (`idBloc`) REFERENCES `blocs` (`idBloc`);

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`idVersement`) REFERENCES `versements` (`idVersement`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`);

--
-- Contraintes pour la table `versements`
--
ALTER TABLE `versements`
  ADD CONSTRAINT `versements_ibfk_1` FOREIGN KEY (`idSelection`) REFERENCES `selection` (`idSelection`),
  ADD CONSTRAINT `versements_ibfk_2` FOREIGN KEY (`idAcheteur`) REFERENCES `acheteur` (`idAcheteur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
