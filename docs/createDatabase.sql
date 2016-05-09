DROP DATABASE IF EXISTS hswalkietalkie;
CREATE DATABASE hswalkietalkie;
USE hswalkietalkie;

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `firstName` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `lastName` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `feedName` varchar(60) COLLATE utf8mb4_bin,
  `feedPassword` varchar(60) COLLATE utf8mb4_bin,
  `email` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `BIC` (
  `bic` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`bic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `Konto` (
  `iban` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `bic` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`iban`),
  FOREIGN KEY (`bic`) REFERENCES BIC (`bic`) ON DELETE CASCADE,
  FOREIGN KEY (`user`) REFERENCES User (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `Follower` (
  `followed` int(11) NOT NULL,
  `follower` int(11) NOT NULL,
  PRIMARY KEY (`followed`, `follower`),
  FOREIGN KEY (`followed`) REFERENCES User (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`follower`) REFERENCES User (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `Posts` (
  `id` int(11) NOT NULL,
  `content` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user`) REFERENCES User (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `Reposts` (
  `user` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `Take_Money` int(11),
  PRIMARY KEY (`user`, `post`),
  FOREIGN KEY (`user`) REFERENCES User (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`post`) REFERENCES Posts (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`Take_Money`) REFERENCES User (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `Votes` (
  `voter` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `vote` boolean,
  PRIMARY KEY (`voter`, `post`),
  FOREIGN KEY (`voter`) REFERENCES User (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`post`) REFERENCES Posts (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `Duell` (
  `id` int(11) NOT NULL,
  `fighter_post` int(11) NOT NULL,
  `fighter_repost` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `winner` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fighter_post`) REFERENCES User (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`fighter_repost`) REFERENCES User (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`post`) REFERENCES Posts (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`winner`) REFERENCES User (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `Ergebnis` (
  `id` int(11) NOT NULL,
  `duell` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `fighter_post_action` int(11) NOT NULL,
  `fighter_repost_action` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`duell`) REFERENCES Duell (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;