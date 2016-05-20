DROP DATABASE IF EXISTS hswalkietalkie;
CREATE DATABASE hswalkietalkie;
USE hswalkietalkie;

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `city` (
  `zip` varchar(5) COLLATE utf8mb4_bin NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`zip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `user` (
  `username` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `firstName` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `feedURL` varchar(255) COLLATE utf8mb4_bin,
  `feedPassword` varchar(255) COLLATE utf8mb4_bin,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_bin DEFAULT "profile_default.png",
  `birthday` DATE NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `housenumber` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `zip` varchar(5) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`username`),
  FOREIGN KEY (`zip`) REFERENCES city (`zip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `bic` (
  `bic` varchar(11) COLLATE utf8mb4_bin NOT NULL,
  `bank` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`bic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `account` (
  `iban` varchar(34) COLLATE utf8mb4_bin NOT NULL,
  `bic` varchar(11) COLLATE utf8mb4_bin,
  `user` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`iban`, `user`),
  FOREIGN KEY (`bic`) REFERENCES bic (`bic`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`user`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `follower` (
  `followed` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `follower` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`followed`, `follower`),
  FOREIGN KEY (`followed`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`follower`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `user` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `parentPost` int(11),
  `datePosted` datetime,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`parentPost`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `postsImg`(
  `postId` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`postId`, `filename`),
  FOREIGN KEY (`postId`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `votes` (
  `voter` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `post` int(11) NOT NULL,
  `vote` boolean,
  PRIMARY KEY (`voter`, `post`),
  FOREIGN KEY (`voter`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`post`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `cashtag` (
  `cashtag` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`cashtag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `cashtagPost` (
  `cashtag` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `postId` int(11) NOT NULL,
  PRIMARY KEY (`cashtag`, `postId`),
  FOREIGN KEY (`cashtag`) REFERENCES cashtag (`cashtag`) ON DELETE CASCADE  ON UPDATE CASCADE,
  FOREIGN KEY (`postId`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `postID` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `commentTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`userID`) REFERENCES user (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`postID`) REFERENCES posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

COMMIT;
