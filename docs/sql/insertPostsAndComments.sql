INSERT INTO `follower` (`followed`, `follower`) VALUES
('xgadelf', 'xgwsdfe'),
('xgadles', 'xgwsdfe'),
('xgadmmh', 'xgwsdfe'),
('xgwsnde', 'xgwsdfe');

INSERT INTO `posts` (`id`, `content`, `user`, `parentPost`, `datePosted`) VALUES
(1, 'Wow, der erste Post auf HSWalkieTalkie!', 'xgadmmh', NULL, '2016-05-21 11:08:07'),
(2, 'Ich hab gehört man kann sogar Smileys machen.\r\nWeiß einer wie das geht?', 'xgwsnde', NULL, '2016-05-21 11:08:36'),
(3, 'Moin zusammen, weiß jemand, ob auch Reposts klappen?', 'xgadles', NULL, '2016-05-21 11:12:08'),
(4, 'Moin zusammen, weiß jemand, ob auch Reposts klappen?', 'xgadelf', 3, '2016-05-21 11:12:50'),
(5, 'Stark. Man kann ja sogar mehrere Bilder gleichzeitig posten.', 'xgadmmh', NULL, '2016-05-21 11:17:16'),
(6, '... Ich bin einfach nur begeistert von der Seite.\r\nDurch den RSS-Feed links bleib ich immer auf dem Laufenden und alle Namen und Cashtags verlinken auf die Suche hiernach bzw. auf die Profile.\r\nWirklich keine schlechte Arbeit !', 'xgwsnde', NULL, '2016-05-21 11:23:36'),
(7, 'Da fällt mir nur noch eins ein.', 'xgwsdfe', NULL, '2016-05-21 11:34:07');

INSERT INTO `comment` (`id`, `userID`, `postID`, `comment`, `commentTime`) VALUES
(1, 'xgwsdfe', 2, 'Ja, du musst, jeweils ohne Leerzeichen, folgendes tippen:\r\n: ) = :)\r\n: | = :|\r\n: ( = :(\r\n( c ) = (c)', '2016-05-21 09:09:24'),
(2, 'xgwsnde', 2, 'Top, danke :)', '2016-05-21 09:10:33'),
(3, 'xgadelf', 3, 'Klar, einfach auf den blauen Pfeil klicken.\r\nWarte, ich reposte deinen mal, dann kannst du auf meinem Profil sehen, wie das angezeigt wird.', '2016-05-21 09:12:48'),
(4, 'xgwsdfe', 5, 'Nicht schlecht. Kriegt direkt ''nen Upvote :)', '2016-05-21 09:17:54'),
(5, 'xgwsdfe', 6, 'Definitiv. Falls der bürgerliche Name von Nutzern doppelt vorkommt, liefert die Seite sogar in der Suchleiste eine händliche weitere Auswahlmöglichkeit.\r\nIst bekannt in welchem Rahmen das Projekt entstanden ist?', '2016-05-21 09:25:10'),
(6, 'xgadles', 6, 'Ich hab gehört, dass es ein Hochschulprojekt war.', '2016-05-21 09:25:45'),
(7, 'xgwsdfe', 6, 'Wow. Da haben die Studis sich aber reingehängt. \r\nSicherlich ne 1,0 geworden.', '2016-05-21 09:26:19'),
(8, 'xgadmmh', 6, 'Muss wohl. \r\nLäuft ja wirklich 1a. \r\nIch bin richtig überwältig von der Seite !', '2016-05-21 09:28:10'),
(9, 'xgadelf', 6, 'Im Rahmen eines Hochschulprojektes?\r\nDie Seite sollte ein Copyright (c) besitzen !\r\nDie ist Wahnsinn !', '2016-05-21 09:29:06');

INSERT INTO `postsimg` (`postId`, `filename`) VALUES
(5, '5_0.jpg'),
(5, '5_1.png'),
(7, '7_0.jpg');

INSERT INTO `votes` (`voter`, `post`, `vote`) VALUES
('xgwsdfe', 5, 1);