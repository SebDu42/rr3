-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : saiph.dupuisland.net
-- Généré le : mar. 24 déc. 2024 à 18:09
-- Version du serveur : 10.3.39-MariaDB-0+deb10u2-log
-- Version de PHP : 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `rr3`
--

-- --------------------------------------------------------

--
-- Structure de la table `rr3_circuits`
--

CREATE TABLE `rr3_circuits` (
  `ci_id` int(11) NOT NULL,
  `ci_nom` text NOT NULL,
  `ci_localisation` text NOT NULL,
  `ci_image` text DEFAULT NULL,
  `ci_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_circuits_configurations`
--

CREATE TABLE `rr3_circuits_configurations` (
  `cic_id` int(11) NOT NULL,
  `cic_fk_ci_id` int(11) NOT NULL,
  `cic_nom` text DEFAULT NULL,
  `cic_longueur` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses`
--

CREATE TABLE `rr3_courses` (
  `c_id` int(11) NOT NULL,
  `c_fk_ce_id` int(11) NOT NULL,
  `c_rang` int(11) NOT NULL,
  `c_fk_ct_id` int(11) NOT NULL,
  `c_fk_cic_id` int(11) NOT NULL,
  `c_fk_cco_id` int(11) NOT NULL DEFAULT 2,
  `c_ip_min` float DEFAULT NULL,
  `c_nb_tours` int(11) DEFAULT NULL,
  `c_nb_concurrents` int(11) DEFAULT NULL,
  `c_fk_m_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses_categories`
--

CREATE TABLE `rr3_courses_categories` (
  `cca_id` int(11) NOT NULL,
  `cca_fk_ccap_id` int(11) NOT NULL,
  `cca_rang` int(11) NOT NULL,
  `cca_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses_categories_principales`
--

CREATE TABLE `rr3_courses_categories_principales` (
  `ccap_id` int(11) NOT NULL,
  `ccap_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses_conditions`
--

CREATE TABLE `rr3_courses_conditions` (
  `cco_id` int(11) NOT NULL,
  `cco_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses_evenements`
--

CREATE TABLE `rr3_courses_evenements` (
  `ce_id` int(11) NOT NULL,
  `ce_fk_cs_id` int(11) NOT NULL,
  `ce_rang` int(11) NOT NULL,
  `ce_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses_series`
--

CREATE TABLE `rr3_courses_series` (
  `cs_id` int(11) NOT NULL,
  `cs_fk_cca_id` int(11) NOT NULL,
  `cs_rang_principal` int(11) NOT NULL,
  `cs_rang_secondaire` int(11) NOT NULL,
  `cs_nom` text NOT NULL,
  `cs_fk_css_id` int(11) NOT NULL DEFAULT 1,
  `cs_avancement` int(11) NOT NULL,
  `cs_commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses_series_statuts`
--

CREATE TABLE `rr3_courses_series_statuts` (
  `css_id` int(11) NOT NULL,
  `css_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_courses_types`
--

CREATE TABLE `rr3_courses_types` (
  `ct_id` int(11) NOT NULL,
  `ct_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_monnaies`
--

CREATE TABLE `rr3_monnaies` (
  `m_id` int(11) NOT NULL,
  `m_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_utilisateurs`
--

CREATE TABLE `rr3_utilisateurs` (
  `u_id` int(11) NOT NULL,
  `u_fk_ur_id` int(11) NOT NULL,
  `u_nom` text NOT NULL,
  `u_prenom` text NOT NULL,
  `u_courriel` text NOT NULL,
  `u_mot_de_passe` text NOT NULL,
  `u_valide` int(11) NOT NULL,
  `u_cle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_utilisateurs_performances`
--

CREATE TABLE `rr3_utilisateurs_performances` (
  `up_id` int(11) NOT NULL,
  `up_fk_u_id` int(11) NOT NULL,
  `up_fk_c_id` int(11) NOT NULL,
  `up_fk_uv_id` int(11) DEFAULT NULL,
  `up_classement` int(11) NOT NULL DEFAULT 1,
  `up_reputation` int(11) NOT NULL DEFAULT 0,
  `up_recompense` int(11) NOT NULL DEFAULT 0,
  `up_temps` text DEFAULT NULL,
  `up_vitesse` float DEFAULT NULL,
  `up_distance` float DEFAULT NULL,
  `up_nb_depassements` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_utilisateurs_roles`
--

CREATE TABLE `rr3_utilisateurs_roles` (
  `ur_id` int(11) NOT NULL,
  `ur_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_utilisateurs_voitures`
--

CREATE TABLE `rr3_utilisateurs_voitures` (
  `uv_id` int(11) NOT NULL,
  `uv_fk_u_id` int(11) NOT NULL,
  `uv_fk_v_id` int(11) NOT NULL,
  `uv_fk_uvs_id` int(11) NOT NULL,
  `uv_vitesse` int(11) NOT NULL,
  `uv_acceleration` float NOT NULL,
  `uv_freinage` float NOT NULL,
  `uv_adherence` float NOT NULL,
  `uv_ip` float NOT NULL,
  `uv_am_moteur` int(11) NOT NULL,
  `uv_am_transmission` int(11) NOT NULL,
  `uv_am_carrosserie` int(11) NOT NULL,
  `uv_am_suspension` int(11) NOT NULL,
  `uv_am_pot` int(11) NOT NULL,
  `uv_am_freins` int(11) NOT NULL,
  `uv_am_roues` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_utilisateurs_voitures_statuts`
--

CREATE TABLE `rr3_utilisateurs_voitures_statuts` (
  `uvs_id` int(11) NOT NULL,
  `uvs_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_voitures`
--

CREATE TABLE `rr3_voitures` (
  `v_id` int(11) NOT NULL,
  `v_fk_vco_id` int(11) NOT NULL,
  `v_modele` text NOT NULL,
  `v_fk_vcl_id` int(11) NOT NULL,
  `v_fk_vt_id` int(11) NOT NULL,
  `v_prix` int(11) NOT NULL,
  `v_fk_m_id` int(11) NOT NULL,
  `v_url` text DEFAULT NULL,
  `v_vitesse` int(11) NOT NULL,
  `v_acceleration` float NOT NULL,
  `v_freinage` float NOT NULL,
  `v_adherence` float NOT NULL,
  `v_ip` float NOT NULL,
  `v_cout_revision` int(11) NOT NULL,
  `v_duree_revision` time NOT NULL,
  `v_cout_revision_instantanee` int(11) DEFAULT NULL,
  `v_am_moteur_max` int(11) DEFAULT NULL,
  `v_am_transmission_max` int(11) DEFAULT NULL,
  `v_am_carrosserie_max` int(11) DEFAULT NULL,
  `v_am_suspension_max` int(11) DEFAULT NULL,
  `v_am_pot_max` int(11) DEFAULT NULL,
  `v_am_freins_max` int(11) DEFAULT NULL,
  `v_am_roues_max` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_voitures_associees`
--

CREATE TABLE `rr3_voitures_associees` (
  `va_id` int(11) NOT NULL,
  `va_fk_v_id` int(11) NOT NULL,
  `va_fk_cs_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_voitures_classes`
--

CREATE TABLE `rr3_voitures_classes` (
  `vcl_id` int(11) NOT NULL,
  `vcl_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_voitures_constructeurs`
--

CREATE TABLE `rr3_voitures_constructeurs` (
  `vco_id` int(11) NOT NULL,
  `vco_nom` text NOT NULL,
  `vco_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_voitures_exclues`
--

CREATE TABLE `rr3_voitures_exclues` (
  `ve_id` int(11) NOT NULL,
  `ve_fk_va_id` int(11) NOT NULL,
  `ve_fk_ce_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rr3_voitures_transmissions`
--

CREATE TABLE `rr3_voitures_transmissions` (
  `vt_id` int(11) NOT NULL,
  `vt_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rr3_circuits`
--
ALTER TABLE `rr3_circuits`
  ADD PRIMARY KEY (`ci_id`);

--
-- Index pour la table `rr3_circuits_configurations`
--
ALTER TABLE `rr3_circuits_configurations`
  ADD PRIMARY KEY (`cic_id`),
  ADD KEY `cic_fk_ci_id` (`cic_fk_ci_id`);

--
-- Index pour la table `rr3_courses`
--
ALTER TABLE `rr3_courses`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `c_fk_ce_id` (`c_fk_ce_id`),
  ADD KEY `c_fk_ct_id` (`c_fk_ct_id`),
  ADD KEY `c_fk_cic_id` (`c_fk_cic_id`),
  ADD KEY `c_fk_cco_id` (`c_fk_cco_id`),
  ADD KEY `c_fk_m_id` (`c_fk_m_id`) USING BTREE;

--
-- Index pour la table `rr3_courses_categories`
--
ALTER TABLE `rr3_courses_categories`
  ADD PRIMARY KEY (`cca_id`),
  ADD KEY `cca_fk_ccap_id` (`cca_fk_ccap_id`);

--
-- Index pour la table `rr3_courses_categories_principales`
--
ALTER TABLE `rr3_courses_categories_principales`
  ADD PRIMARY KEY (`ccap_id`);

--
-- Index pour la table `rr3_courses_conditions`
--
ALTER TABLE `rr3_courses_conditions`
  ADD PRIMARY KEY (`cco_id`);

--
-- Index pour la table `rr3_courses_evenements`
--
ALTER TABLE `rr3_courses_evenements`
  ADD PRIMARY KEY (`ce_id`),
  ADD KEY `ce_fk_cs_id` (`ce_fk_cs_id`);

--
-- Index pour la table `rr3_courses_series`
--
ALTER TABLE `rr3_courses_series`
  ADD PRIMARY KEY (`cs_id`),
  ADD KEY `cs_fk_cca_id` (`cs_fk_cca_id`),
  ADD KEY `cs_fk_css_id` (`cs_fk_css_id`);

--
-- Index pour la table `rr3_courses_series_statuts`
--
ALTER TABLE `rr3_courses_series_statuts`
  ADD PRIMARY KEY (`css_id`);

--
-- Index pour la table `rr3_courses_types`
--
ALTER TABLE `rr3_courses_types`
  ADD PRIMARY KEY (`ct_id`);

--
-- Index pour la table `rr3_monnaies`
--
ALTER TABLE `rr3_monnaies`
  ADD PRIMARY KEY (`m_id`);

--
-- Index pour la table `rr3_utilisateurs`
--
ALTER TABLE `rr3_utilisateurs`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `u_fk_ur_id` (`u_fk_ur_id`);

--
-- Index pour la table `rr3_utilisateurs_performances`
--
ALTER TABLE `rr3_utilisateurs_performances`
  ADD PRIMARY KEY (`up_id`),
  ADD KEY `up_fk_u_id` (`up_fk_u_id`),
  ADD KEY `up_fk_c_id` (`up_fk_c_id`),
  ADD KEY `up_fk_uv_id` (`up_fk_uv_id`);

--
-- Index pour la table `rr3_utilisateurs_roles`
--
ALTER TABLE `rr3_utilisateurs_roles`
  ADD PRIMARY KEY (`ur_id`);

--
-- Index pour la table `rr3_utilisateurs_voitures`
--
ALTER TABLE `rr3_utilisateurs_voitures`
  ADD PRIMARY KEY (`uv_id`),
  ADD KEY `uv_fk_u_id` (`uv_fk_u_id`),
  ADD KEY `uv_fk_v_id` (`uv_fk_v_id`),
  ADD KEY `uv_fk_uvs_id` (`uv_fk_uvs_id`);

--
-- Index pour la table `rr3_utilisateurs_voitures_statuts`
--
ALTER TABLE `rr3_utilisateurs_voitures_statuts`
  ADD PRIMARY KEY (`uvs_id`);

--
-- Index pour la table `rr3_voitures`
--
ALTER TABLE `rr3_voitures`
  ADD PRIMARY KEY (`v_id`),
  ADD KEY `v_fk_vco_id` (`v_fk_vco_id`),
  ADD KEY `v_fk_vcl_id` (`v_fk_vcl_id`),
  ADD KEY `v_fk_vt_id` (`v_fk_vt_id`),
  ADD KEY `v_fk_m_id` (`v_fk_m_id`);

--
-- Index pour la table `rr3_voitures_associees`
--
ALTER TABLE `rr3_voitures_associees`
  ADD PRIMARY KEY (`va_id`),
  ADD KEY `va_fk_v_id` (`va_fk_v_id`),
  ADD KEY `va_fk_cs_id` (`va_fk_cs_id`);

--
-- Index pour la table `rr3_voitures_classes`
--
ALTER TABLE `rr3_voitures_classes`
  ADD PRIMARY KEY (`vcl_id`);

--
-- Index pour la table `rr3_voitures_constructeurs`
--
ALTER TABLE `rr3_voitures_constructeurs`
  ADD PRIMARY KEY (`vco_id`);

--
-- Index pour la table `rr3_voitures_exclues`
--
ALTER TABLE `rr3_voitures_exclues`
  ADD PRIMARY KEY (`ve_id`),
  ADD KEY `ve_fk_va_id` (`ve_fk_va_id`),
  ADD KEY `ve_fk_ce_id` (`ve_fk_ce_id`);

--
-- Index pour la table `rr3_voitures_transmissions`
--
ALTER TABLE `rr3_voitures_transmissions`
  ADD PRIMARY KEY (`vt_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rr3_circuits`
--
ALTER TABLE `rr3_circuits`
  MODIFY `ci_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_circuits_configurations`
--
ALTER TABLE `rr3_circuits_configurations`
  MODIFY `cic_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses`
--
ALTER TABLE `rr3_courses`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses_categories`
--
ALTER TABLE `rr3_courses_categories`
  MODIFY `cca_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses_categories_principales`
--
ALTER TABLE `rr3_courses_categories_principales`
  MODIFY `ccap_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses_conditions`
--
ALTER TABLE `rr3_courses_conditions`
  MODIFY `cco_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses_evenements`
--
ALTER TABLE `rr3_courses_evenements`
  MODIFY `ce_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses_series`
--
ALTER TABLE `rr3_courses_series`
  MODIFY `cs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses_series_statuts`
--
ALTER TABLE `rr3_courses_series_statuts`
  MODIFY `css_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_courses_types`
--
ALTER TABLE `rr3_courses_types`
  MODIFY `ct_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_monnaies`
--
ALTER TABLE `rr3_monnaies`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_utilisateurs`
--
ALTER TABLE `rr3_utilisateurs`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_utilisateurs_performances`
--
ALTER TABLE `rr3_utilisateurs_performances`
  MODIFY `up_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_utilisateurs_roles`
--
ALTER TABLE `rr3_utilisateurs_roles`
  MODIFY `ur_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_utilisateurs_voitures`
--
ALTER TABLE `rr3_utilisateurs_voitures`
  MODIFY `uv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_utilisateurs_voitures_statuts`
--
ALTER TABLE `rr3_utilisateurs_voitures_statuts`
  MODIFY `uvs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_voitures`
--
ALTER TABLE `rr3_voitures`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_voitures_associees`
--
ALTER TABLE `rr3_voitures_associees`
  MODIFY `va_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_voitures_classes`
--
ALTER TABLE `rr3_voitures_classes`
  MODIFY `vcl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_voitures_constructeurs`
--
ALTER TABLE `rr3_voitures_constructeurs`
  MODIFY `vco_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_voitures_exclues`
--
ALTER TABLE `rr3_voitures_exclues`
  MODIFY `ve_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rr3_voitures_transmissions`
--
ALTER TABLE `rr3_voitures_transmissions`
  MODIFY `vt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `rr3_circuits_configurations`
--
ALTER TABLE `rr3_circuits_configurations`
  ADD CONSTRAINT `rr3_circuits_configurations_ibfk_1` FOREIGN KEY (`cic_fk_ci_id`) REFERENCES `rr3_circuits` (`ci_id`);

--
-- Contraintes pour la table `rr3_courses`
--
ALTER TABLE `rr3_courses`
  ADD CONSTRAINT `rr3_courses_ibfk_1` FOREIGN KEY (`c_fk_ce_id`) REFERENCES `rr3_courses_evenements` (`ce_id`),
  ADD CONSTRAINT `rr3_courses_ibfk_2` FOREIGN KEY (`c_fk_cic_id`) REFERENCES `rr3_circuits_configurations` (`cic_id`),
  ADD CONSTRAINT `rr3_courses_ibfk_3` FOREIGN KEY (`c_fk_cco_id`) REFERENCES `rr3_courses_conditions` (`cco_id`),
  ADD CONSTRAINT `rr3_courses_ibfk_4` FOREIGN KEY (`c_fk_ct_id`) REFERENCES `rr3_courses_types` (`ct_id`),
  ADD CONSTRAINT `rr3_courses_ibfk_5` FOREIGN KEY (`c_fk_m_id`) REFERENCES `rr3_monnaies` (`m_id`);

--
-- Contraintes pour la table `rr3_courses_categories`
--
ALTER TABLE `rr3_courses_categories`
  ADD CONSTRAINT `rr3_courses_categories_ibfk_1` FOREIGN KEY (`cca_fk_ccap_id`) REFERENCES `rr3_courses_categories_principales` (`ccap_id`);

--
-- Contraintes pour la table `rr3_courses_evenements`
--
ALTER TABLE `rr3_courses_evenements`
  ADD CONSTRAINT `rr3_courses_evenements_ibfk_1` FOREIGN KEY (`ce_fk_cs_id`) REFERENCES `rr3_courses_series` (`cs_id`);

--
-- Contraintes pour la table `rr3_courses_series`
--
ALTER TABLE `rr3_courses_series`
  ADD CONSTRAINT `rr3_courses_series_ibfk_1` FOREIGN KEY (`cs_fk_cca_id`) REFERENCES `rr3_courses_categories` (`cca_id`),
  ADD CONSTRAINT `rr3_courses_series_ibfk_2` FOREIGN KEY (`cs_fk_css_id`) REFERENCES `rr3_courses_series_statuts` (`css_id`);

--
-- Contraintes pour la table `rr3_utilisateurs`
--
ALTER TABLE `rr3_utilisateurs`
  ADD CONSTRAINT `rr3_utilisateurs_ibfk_1` FOREIGN KEY (`u_fk_ur_id`) REFERENCES `rr3_utilisateurs_roles` (`ur_id`);

--
-- Contraintes pour la table `rr3_utilisateurs_performances`
--
ALTER TABLE `rr3_utilisateurs_performances`
  ADD CONSTRAINT `rr3_utilisateurs_performances_ibfk_1` FOREIGN KEY (`up_fk_u_id`) REFERENCES `rr3_utilisateurs` (`u_id`),
  ADD CONSTRAINT `rr3_utilisateurs_performances_ibfk_2` FOREIGN KEY (`up_fk_c_id`) REFERENCES `rr3_courses` (`c_id`),
  ADD CONSTRAINT `rr3_utilisateurs_performances_ibfk_3` FOREIGN KEY (`up_fk_uv_id`) REFERENCES `rr3_utilisateurs_voitures` (`uv_id`);

--
-- Contraintes pour la table `rr3_utilisateurs_voitures`
--
ALTER TABLE `rr3_utilisateurs_voitures`
  ADD CONSTRAINT `rr3_utilisateurs_voitures_ibfk_1` FOREIGN KEY (`uv_fk_u_id`) REFERENCES `rr3_utilisateurs` (`u_id`),
  ADD CONSTRAINT `rr3_utilisateurs_voitures_ibfk_2` FOREIGN KEY (`uv_fk_v_id`) REFERENCES `rr3_voitures` (`v_id`),
  ADD CONSTRAINT `rr3_utilisateurs_voitures_ibfk_3` FOREIGN KEY (`uv_fk_uvs_id`) REFERENCES `rr3_utilisateurs_voitures_statuts` (`uvs_id`);

--
-- Contraintes pour la table `rr3_voitures`
--
ALTER TABLE `rr3_voitures`
  ADD CONSTRAINT `rr3_voitures_ibfk_1` FOREIGN KEY (`v_fk_vco_id`) REFERENCES `rr3_voitures_constructeurs` (`vco_id`),
  ADD CONSTRAINT `rr3_voitures_ibfk_2` FOREIGN KEY (`v_fk_vcl_id`) REFERENCES `rr3_voitures_classes` (`vcl_id`),
  ADD CONSTRAINT `rr3_voitures_ibfk_3` FOREIGN KEY (`v_fk_vt_id`) REFERENCES `rr3_voitures_transmissions` (`vt_id`),
  ADD CONSTRAINT `rr3_voitures_ibfk_4` FOREIGN KEY (`v_fk_m_id`) REFERENCES `rr3_monnaies` (`m_id`);

--
-- Contraintes pour la table `rr3_voitures_associees`
--
ALTER TABLE `rr3_voitures_associees`
  ADD CONSTRAINT `rr3_voitures_associees_ibfk_1` FOREIGN KEY (`va_fk_cs_id`) REFERENCES `rr3_courses_series` (`cs_id`),
  ADD CONSTRAINT `rr3_voitures_associees_ibfk_2` FOREIGN KEY (`va_fk_v_id`) REFERENCES `rr3_voitures` (`v_id`);

--
-- Contraintes pour la table `rr3_voitures_exclues`
--
ALTER TABLE `rr3_voitures_exclues`
  ADD CONSTRAINT `rr3_voitures_exclues_ibfk_1` FOREIGN KEY (`ve_fk_ce_id`) REFERENCES `rr3_courses_evenements` (`ce_id`),
  ADD CONSTRAINT `rr3_voitures_exclues_ibfk_2` FOREIGN KEY (`ve_fk_va_id`) REFERENCES `rr3_voitures_associees` (`va_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
