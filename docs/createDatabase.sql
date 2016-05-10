DROP DATABASE IF EXISTS hswalkietalkie;
CREATE DATABASE hswalkietalkie;
USE hswalkietalkie;

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `user` (
  `username` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `firstName` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `lastName` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `feedName` varchar(60) COLLATE utf8mb4_bin,
  `feedPassword` varchar(60) COLLATE utf8mb4_bin,
  `email` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `bic` (
  `bic` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`bic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `konto` (
  `iban` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `bic` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `user` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`iban`),
  FOREIGN KEY (`bic`) REFERENCES bic (`bic`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`user`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `follower` (
  `followed` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `follower` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`followed`, `follower`),
  FOREIGN KEY (`followed`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`follower`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `content` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `user` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `reposts` (
  `user` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `post` int(11) NOT NULL,
  `takeMoney` varchar(60) COLLATE utf8mb4_bin,
  PRIMARY KEY (`user`, `post`),
  FOREIGN KEY (`user`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`post`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`takeMoney`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `votes` (
  `voter` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `post` int(11) NOT NULL,
  `vote` boolean,
  PRIMARY KEY (`voter`, `post`),
  FOREIGN KEY (`voter`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`post`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `duell` (
  `id` int(11) NOT NULL,
  `fighterPost` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `fighterRepost` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `post` int(11) NOT NULL,
  `winner` varchar(60) COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fighterPost`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`fighterRepost`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`post`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`winner`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `ergebnis` (
  `id` int(11) NOT NULL,
  `duell` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `fighterPostAction` int(11) NOT NULL,
  `fighterRepostAction` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`duell`) REFERENCES Duell (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;