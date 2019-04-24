-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 23 avr. 2019 à 00:22
-- Version du serveur :  10.1.37-MariaDB
-- Version de PHP :  7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `librairie`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adresses`
--

INSERT INTO `adresses` (`id`, `nom`) VALUES
(1, 'Domicile'),
(2, 'Vacances'),
(3, 'Villa');

-- --------------------------------------------------------

--
-- Structure de la table `adresses_infos`
--

CREATE TABLE `adresses_infos` (
  `id_user` int(11) NOT NULL,
  `id_adresse` int(11) NOT NULL,
  `num` text NOT NULL,
  `rue` text NOT NULL,
  `code_postal` int(11) NOT NULL,
  `ville` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adresses_infos`
--

INSERT INTO `adresses_infos` (`id_user`, `id_adresse`, `num`, `rue`, `code_postal`, `ville`) VALUES
(13, 1, '6', 'rue Christian Pfister', 57000, 'Metz'),
(14, 2, '19', 'Avenue des Tilleuls', 51380, 'Sommesous'),
(16, 3, '66', 'Place des riches', 66000, 'Barcares');

-- --------------------------------------------------------

--
-- Structure de la table `auteurs`
--

CREATE TABLE `auteurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `sexe` varchar(20) NOT NULL,
  `date_naissance` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `auteurs`
--

INSERT INTO `auteurs` (`id`, `nom`, `sexe`, `date_naissance`) VALUES
(1, 'Dazaï Osamu', 'M', '2019-04-10 00:00:00'),
(2, 'George Orwell', 'M', '2019-01-15 00:00:00'),
(3, 'Marcel Ruby', 'M', '2018-05-24 00:00:00'),
(4, 'Stephen King', 'M', '2019-04-22 00:00:00'),
(5, 'Primo Levi', 'M', '2019-04-22 00:00:00'),
(6, 'Laure Adler', 'F', '2019-04-08 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `auteurs_livres`
--

CREATE TABLE `auteurs_livres` (
  `id_livre` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `auteurs_livres`
--

INSERT INTO `auteurs_livres` (`id_livre`, `id_auteur`) VALUES
(5, 5),
(4, 4),
(3, 2),
(2, 3),
(1, 1),
(6, 6);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `type_paiement` varchar(100) NOT NULL DEFAULT 'paypal',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `id_user`, `type_paiement`, `date`) VALUES
(50, 13, 'paypal', '2019-04-22 23:30:58'),
(51, 13, 'paysafecard', '2019-04-22 23:31:28'),
(52, 13, 'carte bancaire', '2019-04-22 23:31:49'),
(53, 13, 'virement', '2019-04-22 23:32:33');

-- --------------------------------------------------------

--
-- Structure de la table `contenu_commande`
--

CREATE TABLE `contenu_commande` (
  `id_commande` int(11) NOT NULL,
  `id_livres` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contenu_commande`
--

INSERT INTO `contenu_commande` (`id_commande`, `id_livres`, `quantite`) VALUES
(50, 3, 11),
(51, 2, 5),
(52, 6, 30),
(53, 2, 4),
(53, 5, 2);

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `descriptif` longtext NOT NULL,
  `jaquette` text,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `genre` varchar(100) NOT NULL,
  `format` varchar(60) NOT NULL DEFAULT 'Grand format',
  `prix` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `descriptif`, `jaquette`, `date`, `genre`, `format`, `prix`) VALUES
(1, 'La déchéance d\'un homme', 'La biotechnique contemporaine menace-t-elle d\'altérer la nature humaine et de nous propulser ainsi dans une \"post-humanité\" effrayante ? La nature humaine modèle et détermine les différents types possibles de régimes politiques. Toute technique assez puissante pour remodeler ce que nous sommes menace potentiellement la démocratie libérale et la nature de la politique elle-même. Nous devons refuser ces mondes futurs qui nous sont proposés sous le faux étendard de la liberté - qu\'il soit celui des droits de reproduction illimités ou celui de la recherche scientifique sans entraves. La liberté véritable signifie la liberté, pour les communautés politiques, de protéger les valeurs qui les fondent contre la révolution biologique d\'aujourd\'hui.', 'https://m.media-amazon.com/images/I/81-fSkO3kkL._AC_UL436_.jpg', '1990-09-10 13:39:43', 'Historique', 'Grand format', 30),
(2, 'Le Livre de la déportation', 'Il y a cinquante ans,les troupes alliees,au fur et a mesure de leur avance,liberaient les camps de la mort et revelaient au monde l\'horreur du système concentrationnaire nazi.\r\nLes camps de concentration-en perticulier ceux de Buchenvald,Dachau et Ravensbrück-et les camps d\'extermination-surtout Auschwitz-ont fait l\'objet de nombreux essais et témoignages.Mais \"le livre de la deportation\" est le premier ouvrage d\'ensemble sur les douze camps de concentration et les six camps d\'extermination batis par l\'empire ss.\r\nGrace a lui,nous les découvrons l\'un apres l\'autre,avec leur histoire,leur organisation,la vie et la mort de leurs victimes-un sinistre bilan fonde sur des documents irrécusables et des témoignages de premiere main,illustre par des cartes et des photographies.\r\nEn décrivant les camps de concentration peuples notamment de résistants de toute l\'Europe,et en confirmant par le texte et l\'image la specificite du genocide condamnant les Juifs a mort uniquement parce qu\'ils etaient nes juifs,ce livre rappelle aussi que,pour Hitler,les handicapes,les homosexuels,les Tsiganes etaient également voues a l\'aneantissement.\r\nUn ouvrage unique qui fait date et deviendra un livre de reference', 'https://m.media-amazon.com/images/I/81IcoiF394L._AC_UL436_.jpg', '1995-01-12 13:39:57', 'Historique', 'Grand format', 49),
(3, '1984', 'De tous les carrefours importants, le visage à la moustache noire vous fixait du regard. BIG BROTHER VOUS REGARDE, répétait la légende, tandis que le regard des yeux noirs pénétrait les yeux de Winston... Au loin, un hélicoptère glissa entre les toits, plana un moment, telle une mouche bleue, puis repartit comme une flèche, dans un vol courbe. C\'était une patrouille qui venait mettre le nez aux fenêtres des gens. Mais les patrouilles n\'avaient pas d\'importance. Seule comptait la Police de la Pensée.', 'https://m.media-amazon.com/images/I/81-Za6Yc+bL._AC_UL436_.jpg', '1972-11-16 13:40:55', 'Science-Fiction', 'Grand format', 19),
(4, 'Désolation', 'La route 50 coupe droit à travers le désert du Nevada, sous un soleil écrasant. On n\'y entend que le jappement lointain des coyotes. C\'est là qu\'un flic étrange, un colosse aux méthodes très particulières, arrête des voyageurs sous des prétextes vagues, puis les contraint de le suivre à la ville voisine Désolation. Et le cauchemar commence... \r\n\r\nAprès plus de vingt romans, best-sellers planétaires, Stephen King démontre avec éclat qu\'il n\'a rien perdu de sa puissance d\'invention. Ce thriller éprouvant, au goût d\'apocalypse, nous entraîne plus loin que jamais dans la lutte éternelle du Bien et du Mal', 'https://m.media-amazon.com/images/I/81JjfPUDB3L._AC_UL436_.jpg', '2004-09-06 13:42:24', 'Horreur', 'Grand format', 59),
(5, 'Si c\'est un homme', 'Ce livre est sans conteste l\'un des témoignages les plus bouleversants sur l\'expérience indicible des camps d\'extermination. Primo Levi y décrit la folie meurtrière du nazisme qui culmine dans la négation de l\'appartenance des juifs à l\'humanité. Le passage où l\'auteur décrit le regard de ce dignitaire nazi qui lui parle sans le voir, comme s\'il était transparent et n\'existait pas en tant qu\'homme, figure parmi les pages qui font le mieux comprendre que l\'holocauste a d\'abord été une négation de l\'humain en l\'autre.\r\nSi rien ne prédisposait l\'ingénieur chimiste qu\'était Primo Levi à écrire, son témoignage est pourtant devenu un livre qu\'il importe à chaque membre de l\'espèce humaine d\'avoir lu pour que la nuit et le brouillard de l\'oubli ne recouvrent pas à tout jamais le souvenir de l\'innommable, pour que jamais plus la question de savoir \"si c\'est un homme\" ne se pose. De ce devoir de mémoire, l\'auteur s\'est acquitté avant de mettre fin à ses jours, tant il semble difficile de vivre hanté par les fantômes de ces corps martyrisés et de ces voix étouffées. --Paul Klein', 'https://m.media-amazon.com/images/I/81CkNKLNoFL._AC_UL436_.jpg', '2019-04-18 11:44:14', 'Historique', 'Grand format', 16),
(6, 'Les femmes qui lisent sont dangereuses', 'Les femmes et la lecture dans l\'art occidental \" Les livres ne sont pas des objets comme les autres pour les femmes ; depuis l\'aube du christianisme jusqu\'à aujourd\'hui, entre nous et eux, circule un courant chaud, une affinité secrète, une relation étrange et singulière tissée d\'interdits, d\'appropriations, de réincorporations. \"', 'https://images-na.ssl-images-amazon.com/images/I/51Kh9Y3jH8L._SX377_BO1,204,203,200_.jpg', '2019-04-22 15:38:52', 'Tragédie', 'Poche', 9);

-- --------------------------------------------------------

--
-- Structure de la table `modes_paiement`
--

CREATE TABLE `modes_paiement` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT 'paypal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `modes_paiement`
--

INSERT INTO `modes_paiement` (`id`, `nom`) VALUES
(1, 'paypal'),
(2, 'carte bancaire'),
(3, 'virement'),
(4, 'paysafecard');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `sexe` varchar(10) NOT NULL DEFAULT 'h',
  `email` varchar(200) NOT NULL,
  `motdepasse` varchar(100) NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` int(11) NOT NULL DEFAULT '0',
  `token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `sexe`, `email`, `motdepasse`, `date_inscription`, `isActive`, `token`) VALUES
(13, 'GrapsZ', 'h', 'mickael.thaize@hotmail.fr', '9cf95dacd226dcf43da376cdb6cbba7035218921', '2019-04-17 16:16:43', 1, ''),
(14, 'robert', 'h', 'mickael.thaize@orange.fr', '9cf95dacd226dcf43da376cdb6cbba7035218921', '2019-04-18 10:45:58', 1, ''),
(16, 'Ektral', 'f', 'live@gta-fivelife.fr', '9cf95dacd226dcf43da376cdb6cbba7035218921', '2019-04-20 00:37:26', 1, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `adresses_infos`
--
ALTER TABLE `adresses_infos`
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_adresse` (`id_adresse`);

--
-- Index pour la table `auteurs`
--
ALTER TABLE `auteurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `auteurs_livres`
--
ALTER TABLE `auteurs_livres`
  ADD KEY `auteurs_livres_ibfk_1` (`id_auteur`),
  ADD KEY `auteurs_livres_ibfk_2` (`id_livre`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commandes_ibfk_1` (`id_user`);

--
-- Index pour la table `contenu_commande`
--
ALTER TABLE `contenu_commande`
  ADD KEY `contenu_commande_ibfk_2` (`id_livres`),
  ADD KEY `id_commande` (`id_commande`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modes_paiement`
--
ALTER TABLE `modes_paiement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `auteurs`
--
ALTER TABLE `auteurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `modes_paiement`
--
ALTER TABLE `modes_paiement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresses_infos`
--
ALTER TABLE `adresses_infos`
  ADD CONSTRAINT `adresses_infos_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adresses_infos_ibfk_2` FOREIGN KEY (`id_adresse`) REFERENCES `adresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `auteurs_livres`
--
ALTER TABLE `auteurs_livres`
  ADD CONSTRAINT `auteurs_livres_ibfk_1` FOREIGN KEY (`id_auteur`) REFERENCES `auteurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auteurs_livres_ibfk_2` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contenu_commande`
--
ALTER TABLE `contenu_commande`
  ADD CONSTRAINT `contenu_commande_ibfk_2` FOREIGN KEY (`id_livres`) REFERENCES `livres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contenu_commande_ibfk_3` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
