-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Mai 2015 um 20:53
-- Server Version: 5.6.21
-- PHP-Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `fusio`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_action`
--

CREATE TABLE IF NOT EXISTS `fusio_action` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `config` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_action`
--

INSERT INTO `fusio_action` (`id`, `name`, `class`, `config`) VALUES
(6, 'actual query', 'Fusio\\Action\\SqlFetchAll', 'a:3:{s:10:"connection";i:1;s:3:"sql";s:26:"SELECT * FROM fusio_routes";s:12:"propertyName";s:3:"foo";}'),
(7, 'static response', 'Fusio\\Action\\StaticResponse', 'a:1:{s:8:"response";s:118:"{\n    "foo": "bar", \n    "bar": "some",\n    "user": [{\n        "title": "news"\n    },{\n        "title": "foo"\n    }]\n}";}'),
(11, 'Hello world', 'Fusio\\Action\\StaticResponse', 'a:1:{s:8:"response";s:19:"{"Hello": "World!"}";}'),
(12, 'select all actions', 'Fusio\\Action\\SqlFetchAll', 'a:2:{s:10:"connection";i:1;s:3:"sql";s:28:"SELECT * FROM `fusio_action`";}'),
(13, 'werwerwer', 'Fusio\\Action\\SqlFetchAll', 'a:3:{s:10:"connection";i:1;s:3:"sql";s:26:"SELECT * FROM fusio_action";s:12:"propertyName";s:3:"foo";}'),
(14, 'wordpress query', 'Fusio\\Action\\SqlFetchAll', 'a:3:{s:10:"connection";i:7;s:3:"sql";s:165:"SELECT \n    post_author AS author, \n    post_date_gmt AS date, \n    post_content AS content, \n    post_title AS title, \n    post_status AS status \nFROM \n    wp_posts";s:12:"propertyName";s:4:"post";}'),
(15, 'wordpress query row', 'Fusio\\Action\\SqlFetchRow', 'a:2:{s:10:"connection";i:7;s:3:"sql";s:189:"SELECT \n    post_author AS author, \n    post_date_gmt AS date, \n    post_content AS content, \n    post_title AS title, \n    post_status AS status \nFROM \n    wp_posts\nWHERE\n    ID = :post_id";}'),
(16, 'wordpress insert entry', 'Fusio\\Action\\SqlExecute', 'a:2:{s:10:"connection";i:7;s:3:"sql";s:176:"INSERT INTO wp_posts SET \n    post_status = {{status}},\n    post_title = {{title}},\n    post_content = {{content}},\n    post_date = {{date}},\n    post_author = {{author}}\n    \n";}'),
(17, 'some query', 'Fusio\\Action\\SqlFetchAll', 'a:3:{s:10:"connection";i:1;s:3:"sql";s:26:"SELECT * FROM fusio_schema";s:12:"propertyName";s:4:"test";}'),
(18, 'Void', 'Fusio\\Action\\StaticResponse', 'a:1:{s:8:"response";s:32:"{\n    "message": "no-response"\n}";}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app`
--

CREATE TABLE IF NOT EXISTS `fusio_app` (
`id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `name` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `appKey` varchar(255) NOT NULL,
  `appSecret` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_app`
--

INSERT INTO `fusio_app` (`id`, `userId`, `status`, `name`, `url`, `appKey`, `appSecret`, `date`) VALUES
(1, 1, 1, 'Backend', 'http://fusio-project.org', '40d05050-52df-4982-9550-09f46522b73b', 'dede66744fffadc418f8aa87cb843f66004c070b200b2c224800d6d2f6b11e32', '0000-00-00 00:00:00'),
(3, 1, 3, 'asdasdsad', 'asdasd', '0cdabe60-2fed-4b8d-8223-3a66ee39831b', '36abc2c9cba646782fc83c3117b709d773a671b180c5a8a6a926d85393e75cee', '2015-02-15 12:44:41'),
(4, 1, 1, 'sadas', 'dasdasd', '58606036-5ba5-46d5-8112-25c2d12f132d', '9ecaf15df355ecac3face62f05633c3206879a2fddd92d74494e322c8f9ca946', '2015-02-15 12:44:58'),
(5, 1, 1, 'werwerwer', 'werwerwer', '5347307d-d801-4075-9aaa-a21a29a448c5', '342cefac55939b31cd0a26733f9a4f061c0829ed87dae7caff50feaa55aff23d', '2015-02-22 22:19:07'),
(7, 1, 2, 'dsfsdf', 'sdfsdf', '716d1a9b-cf8d-4a66-9fff-fc489b1668d9', '17d83b1559e5728710988710235497b8c34835a53b132854289406fb68d6dba9', '2015-03-07 08:45:42'),
(8, 15, 1, 'test', 'http://google.de', '7c14809c-544b-43bd-9002-23e1c2de6067', 'bb0574181eb4a1326374779fe33e90e2c427f28ab0fc1ffd168bfd5309ee7caa', '2015-03-08 17:25:51'),
(10, 15, 1, 'foo app', 'http://google.de', 'f46af464-f7eb-4d04-8661-13063a30826b', '17b882987298831a3af9c852f9cd0219d349ba61fcf3fc655ac0f07eece951f9', '2015-03-08 17:26:56'),
(13, 1, 1, 'test2asd', 'http://google.com', '70896e3c-4d75-4497-b007-7ceeb06a5e1f', '82ada90a51b9d1937d8d7aeea4a1cdf5966c40b06c1a535cd3cf9bde017f0697', '2015-03-10 18:09:10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app_scope`
--

CREATE TABLE IF NOT EXISTS `fusio_app_scope` (
`id` int(10) NOT NULL,
  `appId` int(10) NOT NULL,
  `scopeId` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_app_scope`
--

INSERT INTO `fusio_app_scope` (`id`, `appId`, `scopeId`) VALUES
(13, 13, 5),
(14, 10, 8),
(15, 10, 3),
(16, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app_token`
--

CREATE TABLE IF NOT EXISTS `fusio_app_token` (
`id` int(10) NOT NULL,
  `appId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '1',
  `token` varchar(512) NOT NULL,
  `scope` varchar(255) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `expire` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_app_token`
--

INSERT INTO `fusio_app_token` (`id`, `appId`, `userId`, `status`, `token`, `scope`, `ip`, `expire`, `date`) VALUES
(1, 1, 1, 1, 'b88453bec3b2ebc8faeb2a330efbf56ed36253049061b689878d65120520ddec', 'backend', '127.0.0.1', 0, '2015-02-27 20:26:00'),
(2, 1, 1, 1, 'f74191a1f0eb8e5c544c7b4ede7b4709bc778f4b31f05a40edbba3e30ac8db2d', 'backend', '127.0.0.1', 0, '2015-02-27 20:27:10'),
(3, 13, 1, 1, 'ca4c93702b89ba5ef96ce6160b97f1c00f7f1e677490fac7b3be81203a93cba7', 'backend', '127.0.0.1', 1426954697, '2015-02-27 20:28:13'),
(4, 13, 1, 1, '182579103084483e79f1791e8464086985861daa6b3062af899e9d321519ddee', 'backend', '127.0.0.1', 1426954697, '2015-02-27 20:28:42'),
(5, 1, 1, 1, 'df28d729af100b91195d7b7dede2d22c33f6f5f1e5b896fff3561d71a9d35079', 'backend', '127.0.0.1', 1425070231, '2015-02-27 20:50:31'),
(6, 1, 1, 1, '2c999dc2e90e905b3dfdaf7a3590df69725d62893c2e4fb1acdebc45736e9e0f', 'backend', '127.0.0.1', 1425071256, '2015-02-27 21:07:36'),
(7, 1, 1, 1, 'b04c6f936e387ba7f935cd8b7f6d98ea727db5f2177383df8f865d2bf14ae7f7', 'backend', '127.0.0.1', 1425071369, '2015-02-27 21:09:29'),
(8, 1, 1, 1, 'af0131f6b43d85b13d578ffd085cc3203c4efe8979e4df9ffd6eaab455585bb2', 'backend', '127.0.0.1', 1425071633, '2015-02-27 21:13:53'),
(9, 1, 1, 1, 'a2c2b0655232ba6e3a310bf14dceb49722d0e00f14dac6289c07a96de9390699', 'backend', '127.0.0.1', 1425073215, '2015-02-27 21:40:15'),
(10, 1, 1, 1, '8532caca51c91aad66c2809eaabc0a72242473d8220baac67fe2b079366be205', 'backend', '127.0.0.1', 1425172386, '2015-02-28 20:13:06'),
(11, 1, 1, 1, 'd75a293622840c074fbcce0724a6e4825bb96ebb82deb1b23daa0a81b3f9dad7', 'backend', '127.0.0.1', 1425172672, '2015-02-28 20:17:52'),
(12, 1, 1, 1, '0417f2391ccc40abf54971b245202ebb09ba0db8c5d014160fd4f297b6911e54', 'backend', '127.0.0.1', 1425175043, '2015-02-28 20:57:23'),
(24, 1, 1, 1, '70838f8ee5c5c78a60d33001c6c5480703d099ff20e00bbb62ac689fe9071e10', 'backend', '127.0.0.1', 1425186517, '2015-03-01 00:08:37'),
(27, 1, 1, 1, '438d3f989478c069deefdccfb6114ab3bea42623932c68cf13e51735166f81a5', 'backend', '127.0.0.1', 1425224628, '2015-03-01 10:43:48'),
(28, 1, 1, 1, '7dfc8f8a1a9f54bfcd927b8de30b0aea84bbe08b4cc99f3fd073276301de51d8', 'backend', '127.0.0.1', 1425227394, '2015-03-01 11:29:54'),
(29, 1, 1, 1, '8df623dc5e197eb79ef92150412cf6a0c40d3854ea96a2efd07fcf6b2d5e8e6b', 'backend', '127.0.0.1', 1425513999, '2015-03-04 19:06:39'),
(30, 1, 1, 1, '9227b0f0f0a4b1bd8844d17bf731dc9664733ada3e08d7f65e18a8a6809aa873', 'backend', '127.0.0.1', 1425602080, '2015-03-05 19:34:40'),
(31, 1, 1, 1, 'ad1cc7014d711fee4f5e63ad0fd278e0711db45776af381da85f9cfb2711b3fd', 'backend', '127.0.0.1', 1425739097, '2015-03-07 09:38:17'),
(32, 1, 1, 1, '3ac9184d86fb0d39c295d52b37df1ca446017edd79f6cee6d6864773326b1045', 'backend', '127.0.0.1', 1425760735, '2015-03-07 15:38:55'),
(33, 1, 1, 1, 'dd0c413cabcc0f56937b983718417af4a2efe5dfe34fd3cf057afb6b4558d9b9', 'backend', '127.0.0.1', 1425832629, '2015-03-08 11:37:09'),
(34, 1, 1, 1, 'cf5e1f1cfdefd1631b90c42c9760a5bf26c06caa4844b3d167cda6b746b539bc', 'backend', '127.0.0.1', 1425856434, '2015-03-08 18:13:54'),
(35, 1, 1, 1, '3a6dd742d2f2ecbcf88e63f900320ee14dd20d8cda2b3dd9e3aa020bfa276a21', 'backend', '127.0.0.1', 1426032200, '2015-03-10 19:03:20'),
(36, 1, 1, 1, '85017a440eb1e0ead28b7f5007cd3e886f8362af7782ef3046aae2049a91da86', 'backend', '127.0.0.1', 1426043333, '2015-03-10 22:08:53'),
(37, 1, 1, 1, '16cae2dea28fc86bbb00578cd7d726df9e29e7c58567686c613f8835950c2d49', 'backend', '127.0.0.1', 1426123600, '2015-03-11 20:26:40'),
(38, 1, 1, 1, '5a4080d63efbf1e96cc2780032bf7b676339c4c5a5d1def5c78ec99e703385a0', 'backend', '127.0.0.1', 1426385736, '2015-03-14 21:15:36'),
(39, 1, 1, 1, '83c9434fc327d5278b378bb37630af87899c71ff11359b5ad4a82baae1c8b6c5', 'backend', '127.0.0.1', 1426914790, '2015-03-21 00:13:10'),
(40, 1, 1, 1, 'a491439e3115e9e7a88ef5d3bde041656f50870c3da54c76fa657813406ee290', 'backend', '127.0.0.1', 1426956558, '2015-03-21 11:49:18'),
(41, 1, 1, 2, 'a04d14eeae2156daf1c511275cde7fe8877c1a77d7f5b75ff0050cf8b5de4cef', 'backend', '127.0.0.1', 1426978683, '2015-03-21 17:58:03'),
(42, 1, 1, 1, 'f1830c04f6ac23cefcbcb04b406d1cdf89ab961960ade81f8260b0956f29a47b', 'backend', '127.0.0.1', 1426979463, '2015-03-21 18:11:03'),
(43, 1, 1, 1, '76247c0651616d8d79e745fc84ca2780663405b1970cd98eb00ecaec75f4024b', 'backend', '127.0.0.1', 1428371809, '2015-04-06 21:56:49'),
(44, 1, 1, 1, '903d96d4e6cc6934c80f178f520dec983d829936372710652d1e1e088f9ecde4', 'backend', '127.0.0.1', 1428536296, '2015-04-08 19:38:16'),
(45, 1, 1, 1, '00f3b43060ae30d73f5fad3c3b1c3429c5d0fa6b60bde87af96afd4e9bb1167c', 'backend', '127.0.0.1', 1428621294, '2015-04-09 19:14:54'),
(46, 1, 1, 1, 'b0c4d6a05129c7a5f6a84d406c4f19476d98bc261f8a05192326b042a352bbba', 'backend', '127.0.0.1', 1429833659, '2015-04-23 20:00:59'),
(47, 1, 1, 1, '92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f', 'backend', '127.0.0.1', 1430868561, '2015-05-05 19:29:21'),
(48, 1, 1, 2, 'bdd1749d8f582807be47f846f6df8bec234ca76420bf248dc8e1a80e92795ca0', 'backend', '127.0.0.1', 1430958056, '2015-05-06 20:20:56'),
(49, 1, 1, 2, 'e57e75eac3e4e20d8f9dfa8d7111feedbad16916823af853c2eb30038d2b6d98', 'backend', '127.0.0.1', 1430939532, '2015-05-06 20:42:12'),
(50, 1, 1, 2, '6e0256b923a48bbf69023ffdc0630bb5de625d19a9cf3cbf0259f0d1dee5a405', 'backend', '127.0.0.1', 1430939989, '2015-05-06 20:49:49'),
(51, 1, 1, 1, '1670a7014b125259772642c599f225906d1d7fd8a0df65adbdcc8c0a64321142', 'backend', '127.0.0.1', 1430940044, '2015-05-06 20:50:44');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_connection`
--

CREATE TABLE IF NOT EXISTS `fusio_connection` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `config` text
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_connection`
--

INSERT INTO `fusio_connection` (`id`, `name`, `class`, `config`) VALUES
(1, 'Native-Connection', 'Fusio\\Connection\\Native', NULL),
(2, 'dfsdfsdf', 'Fusio\\Connection\\DBAL', 'a:0:{}'),
(5, 'sdfsdf', 'Fusio\\Connection\\DBAL', 'a:5:{s:4:"type";s:4:"oci8";s:4:"host";s:4:"sdfs";s:8:"username";s:5:"dfsdf";s:8:"password";s:3:"sdf";s:8:"database";s:6:"sdfsdf";}'),
(6, 'goo', 'Fusio\\Connection\\DBALAdvanced', 'a:2:{s:3:"url";s:12:"mysql://fooo";s:4:"type";s:9:"pdo_mysql";}'),
(7, 'wordpress', 'Fusio\\Connection\\DBAL', 'a:4:{s:4:"type";s:9:"pdo_mysql";s:4:"host";s:9:"127.0.0.1";s:8:"username";s:4:"root";s:8:"database";s:9:"wordpress";}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_log`
--

CREATE TABLE IF NOT EXISTS `fusio_log` (
`id` int(10) NOT NULL,
  `appId` int(10) DEFAULT NULL,
  `routeId` int(10) DEFAULT NULL,
  `ip` varchar(40) NOT NULL,
  `userAgent` varchar(255) NOT NULL,
  `method` varchar(16) NOT NULL,
  `path` varchar(255) NOT NULL,
  `header` text NOT NULL,
  `body` text,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_log`
--

INSERT INTO `fusio_log` (`id`, `appId`, `routeId`, `ip`, `userAgent`, `method`, `path`, `header`, `body`, `date`) VALUES
(1, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:08:21'),
(2, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:08:22'),
(3, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:08:22'),
(4, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:20:54'),
(5, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:20:54'),
(6, NULL, 45, '127.0.0.1', '', 'GET', '/servers', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:44:22'),
(7, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss?format=xml', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:44:42'),
(8, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-27 19:30:35'),
(9, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions?format=json', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-27 19:30:45'),
(10, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:17'),
(11, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:25'),
(12, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:26'),
(13, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:26'),
(14, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:15:54'),
(15, NULL, 59, '127.0.0.1', '', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:15:54'),
(16, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:45'),
(17, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:47'),
(18, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:57'),
(19, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:57'),
(20, NULL, 64, '127.0.0.1', '', 'GET', '/bar/blub', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:27:29'),
(21, NULL, 64, '127.0.0.1', '', 'GET', '/bar/blub', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:27:41'),
(22, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:31:05'),
(23, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:37'),
(24, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(25, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(26, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(27, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(28, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(29, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(30, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(31, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(32, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(33, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(34, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(35, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(36, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(37, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(38, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(39, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(40, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(41, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(42, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:41'),
(43, NULL, 65, '127.0.0.1', '', 'GET', '/some/stuff?foo=SELEC * FROM', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:35:06'),
(45, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:108:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-03-07 18:06:10'),
(46, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:108:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-03-07 18:06:22'),
(47, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-07 18:15:04'),
(48, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-07 18:15:30'),
(49, NULL, 63, '127.0.0.1', '', 'GET', '/foo?fo=fkjlsdjflskdjflorem', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-07 18:23:18'),
(50, NULL, 63, '127.0.0.1', '', 'GET', '/foo?fo=Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea ', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-07 18:23:28'),
(51, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-07 18:25:13'),
(52, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-03-07 18:25:18'),
(53, NULL, 65, '127.0.0.1', '', 'POST', '/some/stuff', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: text/plain; charset=ISO-8859-1\nContent-Length: 17\nExpect: 100-continue', '{\n	"foo": "bar"\n}', '2015-03-07 18:26:04'),
(54, NULL, 65, '127.0.0.1', '', 'POST', '/some/stuff', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 17\nExpect: 100-continue', '{\n	"foo": "bar"\n}', '2015-03-07 18:26:13'),
(55, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:27:05'),
(56, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:33:43'),
(57, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:34:49'),
(58, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:35:20'),
(59, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:35:25'),
(60, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:40:34'),
(61, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:40:47'),
(62, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:41:22'),
(63, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:43:28'),
(64, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:45:55'),
(65, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:48:21'),
(66, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:51:06'),
(67, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:51:43'),
(68, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:52:02'),
(69, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:52:52'),
(70, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:53:59'),
(71, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:55:11'),
(72, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:55:56'),
(73, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:56:34'),
(74, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 20:59:19'),
(75, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:18:25'),
(76, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:25:31'),
(77, NULL, 73, '127.0.0.1', '', 'GET', '/foo/1', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:25:33'),
(78, NULL, 73, '127.0.0.1', '', 'GET', '/foo/2', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:25:35'),
(79, NULL, 73, '127.0.0.1', '', 'GET', '/foo/3', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:25:37'),
(80, NULL, 73, '127.0.0.1', '', 'GET', '/foo/5', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:25:40'),
(81, NULL, 73, '127.0.0.1', '', 'GET', '/foo/5', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:27:16'),
(82, NULL, 63, '127.0.0.1', '', 'GET', '/foo/', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:27:27'),
(83, NULL, 73, '127.0.0.1', '', 'GET', '/foo/5', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:27:30'),
(84, NULL, 73, '127.0.0.1', '', 'GET', '/foo/5', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:27:33'),
(85, NULL, 73, '127.0.0.1', '', 'GET', '/foo/1', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:27:40'),
(86, NULL, 73, '127.0.0.1', '', 'GET', '/foo/1', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:28:35'),
(87, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-11 21:44:12'),
(88, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-03-11 21:44:25'),
(89, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 21:44:51'),
(90, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 21:45:33'),
(91, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:07:54'),
(92, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:10:03'),
(93, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:11:43'),
(94, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:11:50'),
(95, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:12:08');
INSERT INTO `fusio_log` (`id`, `appId`, `routeId`, `ip`, `userAgent`, `method`, `path`, `header`, `body`, `date`) VALUES
(96, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:12:22'),
(97, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:12:31'),
(98, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:12:38'),
(99, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:12:49'),
(100, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:12:57'),
(101, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:13:45'),
(102, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:13:51'),
(103, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:14:53'),
(104, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:15:45'),
(105, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', NULL, '2015-03-11 22:16:09'),
(106, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:16:18'),
(107, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:16:26'),
(108, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:17:34'),
(109, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:17:47'),
(110, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:22:30'),
(111, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:23:17'),
(112, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:23:34'),
(113, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:23:42'),
(114, NULL, 63, '127.0.0.1', '', 'POST', '/foo', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 209\nExpect: 100-continue', '{\n  "status": "publish",\n  "title": "Hello world!",\n  "content": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!",\n  "date": "2015-02-24T19:22:43+00:00",\n  "author": 1\n}', '2015-03-11 22:24:01'),
(115, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-14 21:19:52'),
(116, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-14 21:22:39'),
(117, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-03-21 17:39:06'),
(118, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-06 21:57:08'),
(119, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-09 19:14:14'),
(120, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:01:11'),
(121, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:01:38'),
(122, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:16:48'),
(123, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:17:00'),
(124, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:17:00'),
(125, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:17:34'),
(126, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:17:41'),
(127, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:33:19'),
(128, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:37:07'),
(129, NULL, 48, '127.0.0.1', '', 'GET', '/foo/barssss', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:59:01'),
(130, NULL, 73, '127.0.0.1', '', 'GET', '/foo/bar', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:59:04'),
(131, NULL, 73, '127.0.0.1', '', 'GET', '/foo/bar', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 20:59:50'),
(132, NULL, 75, '127.0.0.1', '', 'GET', '/test', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 21:00:27'),
(133, NULL, 75, '127.0.0.1', '', 'GET', '/test', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-04-23 21:03:49'),
(134, NULL, 75, '127.0.0.1', '', 'GET', '/test', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-04-23 21:04:04'),
(135, NULL, 75, '127.0.0.1', '', 'POST', '/test', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 17\nExpect: 100-continue', '{\n	"foo": "bar"\n}', '2015-04-23 21:04:29'),
(136, NULL, 75, '127.0.0.1', '', 'POST', '/test', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: text/plain\nContent-Length: 17\nExpect: 100-continue', '{\n	"foo": "bar"\n}', '2015-04-23 21:04:59'),
(137, NULL, 75, '127.0.0.1', '', 'POST', '/test', 'Authorization: \nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive\nContent-Type: application/json\nContent-Length: 17\nExpect: 100-continue', '{\n	"foo": "bar"\n}', '2015-04-23 21:05:27'),
(138, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-05-05 21:17:12'),
(139, NULL, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-05-05 21:17:52'),
(140, 1, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-05-05 21:23:23'),
(141, 1, 63, '127.0.0.1', '', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-05-05 21:28:53'),
(142, NULL, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-05-05 22:07:15'),
(143, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-05-05 22:11:10'),
(144, NULL, 63, '127.0.0.1', 'L<b>y</b>nx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nUser-Agent: L<b>y</b>nx 2.8.7\nConnection: Keep-Alive', NULL, '2015-05-05 22:11:27'),
(145, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-05-05 22:11:46'),
(146, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nUser-Agent: Lynx 2.8.7\nConnection: Keep-Alive', NULL, '2015-05-05 22:14:37'),
(147, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nConnection: Keep-Alive\nUser-Agent: Lynx 2.8.7', NULL, '2015-05-05 22:14:42'),
(148, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nConnection: Keep-Alive\nUser-Agent: Lynx 2.8.7', NULL, '2015-05-05 22:14:45'),
(149, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nConnection: Keep-Alive\nUser-Agent: Lynx 2.8.7', NULL, '2015-05-05 22:15:04'),
(150, NULL, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nCache-Control: max-age=0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-05-05 22:18:48'),
(151, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nConnection: Keep-Alive\nUser-Agent: Lynx 2.8.7', NULL, '2015-05-05 22:58:17'),
(152, NULL, 63, '127.0.0.1', 'Lynx 2.8.7', 'GET', '/foo', 'Authorization: Bearer 92d78082b1011e7bef0ae52ff884127cc4dd9d5fbaf8741afaec8f742593e95f\nHost: 127.0.0.1\nConnection: Keep-Alive\nUser-Agent: Lynx 2.8.7', NULL, '2015-05-05 23:30:27'),
(153, NULL, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-05-06 20:35:11'),
(154, NULL, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', 'GET', '/foo', 'Authorization: \nHost: 127.0.0.1\nConnection: keep-alive\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36\nAccept-Encoding: gzip, deflate, sdch\nAccept-Language: de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4', NULL, '2015-05-06 20:41:29');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_routes`
--

CREATE TABLE IF NOT EXISTS `fusio_routes` (
`id` int(11) NOT NULL,
  `methods` varchar(64) NOT NULL,
  `path` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `config` text
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_routes`
--

INSERT INTO `fusio_routes` (`id`, `methods`, `path`, `controller`, `config`) VALUES
(1, 'GET|POST|PUT|DELETE', '/backend', 'Fusio\\Backend\\Application\\Index', NULL),
(2, 'GET|POST|PUT|DELETE', '/backend/routes', 'Fusio\\Backend\\Api\\Routes\\Collection', NULL),
(3, 'GET|POST|PUT|DELETE', '/backend/schema', 'Fusio\\Backend\\Api\\Schema\\Collection', NULL),
(4, 'GET|POST|PUT|DELETE', '/backend/action', 'Fusio\\Backend\\Api\\Action\\Collection', NULL),
(5, 'GET|POST|PUT|DELETE', '/backend/trigger', 'Fusio\\Backend\\Api\\Trigger', NULL),
(6, 'GET|POST|PUT|DELETE', '/backend/connection', 'Fusio\\Backend\\Api\\Connection\\Collection', NULL),
(7, 'GET|POST|PUT|DELETE', '/backend/app', 'Fusio\\Backend\\Api\\App\\Collection', NULL),
(8, 'GET', '/backend/action/list', 'Fusio\\Backend\\Api\\Action\\ListActions::doIndex', NULL),
(9, 'GET|POST|PUT|DELETE', '/backend/settings', 'Fusio\\Backend\\Api\\Settings', NULL),
(10, 'GET', '/backend/log', 'Fusio\\Backend\\Api\\Log\\Collection', NULL),
(11, 'GET', '/backend/action/form', 'Fusio\\Backend\\Api\\Action\\ListActions::doDetail', NULL),
(14, 'GET', '/backend/connection/form', 'Fusio\\Backend\\Api\\Connection\\ListConnections::doDetail', NULL),
(15, 'GET', '/backend/connection/list', 'Fusio\\Backend\\Api\\Connection\\ListConnections::doIndex', NULL),
(42, 'GET|POST|PUT|DELETE', '/backend/routes/:route_id', 'Fusio\\Backend\\Api\\Routes\\Entity', NULL),
(43, 'GET|POST|PUT|DELETE', '/backend/action/:action_id', 'Fusio\\Backend\\Api\\Action\\Entity', NULL),
(44, 'GET|POST|PUT|DELETE', '/backend/schema/:schema_id', 'Fusio\\Backend\\Api\\Schema\\Entity', NULL),
(45, 'GET', '/servers', 'Fusio\\Controller\\SchemaApiController', NULL),
(46, 'POST', '/server/reboot', 'Fusio\\Controller\\SchemaApiController', NULL),
(47, 'GET|POST|PUT|DELETE', '/servser/config', 'Fusio\\Controller\\SchemaApiController', NULL),
(48, 'GET', '/foo/barssss', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":126:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:6;s:6:"action";i:6;}}}}'),
(49, 'GET|POST|PUT|DELETE', '/backend/connection/:connection_id', 'Fusio\\Backend\\Api\\Connection\\Entity', NULL),
(52, 'GET', '/doc', 'PSX\\Controller\\Tool\\DocumentationController::doIndex', NULL),
(53, 'GET', '/doc/:version/*path', 'PSX\\Controller\\Tool\\DocumentationController::doDetail', NULL),
(54, 'GET|POST|PUT|DELETE', '/backend/app/:app_id', 'Fusio\\Backend\\Api\\App\\Entity', NULL),
(55, 'GET', '/backend/log/:log_id', 'Fusio\\Backend\\Api\\Log\\Entity', NULL),
(56, 'GET|POST|PUT|DELETE', '/backend/scope', 'Fusio\\Backend\\Api\\Scope\\Collection', NULL),
(57, 'GET|POST|PUT|DELETE', '/backend/scope/:scope_id', 'Fusio\\Backend\\Api\\Scope\\Entity', NULL),
(58, 'GET|POST', '/backend/token', 'Fusio\\Backend\\Authorization\\Token', NULL),
(59, 'GET', '/all/actions', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:12;}}}}'),
(60, 'GET', '/backend/whoami', 'Fusio\\Backend\\Api\\Authorization\\Whoami', NULL),
(61, 'GET', '/backend/dashboard/incoming_requests', 'Fusio\\Backend\\Api\\Dashboard\\IncomingRequests', NULL),
(62, 'GET', '/backend/dashboard/most_used_routes', 'Fusio\\Backend\\Api\\Dashboard\\MostUsedRoutes', NULL),
(63, 'GET|POST', '/foo', 'Fusio\\Controller\\SchemaApiController', 'a:2:{i:0;C:15:"PSX\\Data\\Record":145:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:5:{s:6:"public";b:1;s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:12;s:6:"action";i:14;}}}i:1;C:15:"PSX\\Data\\Record":129:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:4:"POST";s:7:"request";i:11;s:8:"response";i:1;s:6:"action";i:16;}}}}'),
(64, 'GET', '/bar/blub', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":126:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:6;}}}}'),
(65, 'GET|POST|PUT', '/some/stuff', 'Fusio\\Controller\\SchemaApiController', 'a:3:{i:0;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:11;}}}i:1;C:15:"PSX\\Data\\Record":128:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:4:"POST";s:7:"request";i:1;s:8:"response";i:1;s:6:"action";i:12;}}}i:2;C:15:"PSX\\Data\\Record":126:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"PUT";s:7:"request";i:6;s:8:"response";i:5;s:6:"action";i:6;}}}}'),
(66, 'GET', '/backend/dashboard/most_used_apps', 'Fusio\\Backend\\Api\\Dashboard\\MostUsedApps', NULL),
(67, 'GET', '/backend/dashboard/latest_requests', 'Fusio\\Backend\\Api\\Dashboard\\LatestRequests', NULL),
(68, 'GET', '/backend/dashboard/latest_apps', 'Fusio\\Backend\\Api\\Dashboard\\LatestApps', NULL),
(69, 'GET', '/backend/dashboard/latest_users', 'Fusio\\Backend\\Api\\Dashboard\\LatestUsers', NULL),
(70, 'POST', '/authorization/revoke', 'Fusio\\Authorization\\Revoke', NULL),
(71, 'GET|POST|PUT|DELETE', '/backend/user', 'Fusio\\Backend\\Api\\User\\Collection', NULL),
(72, 'GET|POST|PUT|DELETE', '/backend/user/:user_id', 'Fusio\\Backend\\Api\\User\\Entity', NULL),
(73, 'GET', '/foo/:post_id', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":128:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:11;s:6:"action";i:15;}}}}'),
(74, 'DELETE', '/backend/app/:app_id/token/:token_id', 'Fusio\\Backend\\Api\\App\\Token::doRemove', NULL),
(75, 'POST|PUT|DELETE|GET', '/test', 'Fusio\\Controller\\SchemaApiController', 'a:4:{i:0;C:15:"PSX\\Data\\Record":128:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:4:"POST";s:7:"request";i:1;s:8:"response";i:1;s:6:"action";i:18;}}}i:1;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"PUT";s:7:"request";i:1;s:8:"response";i:1;s:6:"action";i:18;}}}i:2;C:15:"PSX\\Data\\Record":130:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:6:"DELETE";s:7:"request";i:1;s:8:"response";i:1;s:6:"action";i:18;}}}i:3;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:11;}}}}'),
(76, 'GET|POST', '/authorization/token', 'Fusio\\Authorization\\Token', NULL),
(77, 'GET', '/authorization/whoami', 'Fusio\\Authorization\\Whoami', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_schema`
--

CREATE TABLE IF NOT EXISTS `fusio_schema` (
`id` int(10) NOT NULL,
  `extendsId` int(10) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `propertyName` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_schema`
--

INSERT INTO `fusio_schema` (`id`, `extendsId`, `name`, `propertyName`) VALUES
(1, NULL, 'Passthru', 'passthru'),
(5, NULL, 'routes', 'route'),
(6, NULL, 'route collection', 'collection'),
(7, NULL, 'test', 'foo'),
(11, NULL, 'wp post entry', 'post'),
(12, NULL, 'wp collection', 'entry');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_schema_fields`
--

CREATE TABLE IF NOT EXISTS `fusio_schema_fields` (
`id` int(10) NOT NULL,
  `schemaId` int(10) NOT NULL,
  `refId` int(10) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `min` int(10) DEFAULT NULL,
  `max` int(10) DEFAULT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  `enumeration` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_schema_fields`
--

INSERT INTO `fusio_schema_fields` (`id`, `schemaId`, `refId`, `name`, `type`, `required`, `min`, `max`, `pattern`, `enumeration`) VALUES
(15, 5, NULL, 'path', 'String', 0, NULL, NULL, NULL, NULL),
(16, 5, NULL, 'methods', 'String', 0, NULL, NULL, NULL, NULL),
(23, 6, 5, 'foo', 'Array', 0, NULL, NULL, NULL, NULL),
(24, 7, NULL, 'id', 'String', 0, NULL, NULL, NULL, NULL),
(25, 7, NULL, 'name', 'String', 0, NULL, NULL, NULL, NULL),
(26, 7, NULL, 'foo', 'String', 0, NULL, NULL, NULL, NULL),
(27, 7, NULL, 'bar', 'String', 0, NULL, NULL, NULL, NULL),
(28, 7, NULL, 'date', 'Datetime', 0, NULL, NULL, NULL, NULL),
(36, 11, NULL, 'status', 'String', 0, NULL, NULL, NULL, NULL),
(37, 11, NULL, 'title', 'String', 0, NULL, NULL, NULL, NULL),
(38, 11, NULL, 'content', 'String', 0, NULL, NULL, NULL, NULL),
(39, 11, NULL, 'date', 'Datetime', 0, NULL, NULL, NULL, NULL),
(40, 11, NULL, 'author', 'Integer', 0, NULL, NULL, NULL, NULL),
(44, 12, 11, 'post', 'Array', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_scope`
--

CREATE TABLE IF NOT EXISTS `fusio_scope` (
`id` int(10) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_scope`
--

INSERT INTO `fusio_scope` (`id`, `name`) VALUES
(1, 'backend'),
(3, 'foobar'),
(7, 'premium'),
(8, 'rtetertet'),
(5, 'sdfsdfsdf');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_scope_routes`
--

CREATE TABLE IF NOT EXISTS `fusio_scope_routes` (
`id` int(10) NOT NULL,
  `scopeId` int(10) NOT NULL,
  `routeId` int(10) NOT NULL,
  `allow` tinyint(4) NOT NULL,
  `methods` varchar(64) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_scope_routes`
--

INSERT INTO `fusio_scope_routes` (`id`, `scopeId`, `routeId`, `allow`, `methods`) VALUES
(39, 3, 48, 1, 'GET'),
(40, 3, 47, 1, 'POST|PUT'),
(41, 3, 46, 1, ''),
(43, 5, 48, 1, 'GET'),
(44, 5, 45, 1, 'GET'),
(52, 7, 63, 1, 'GET'),
(53, 7, 48, 1, 'GET'),
(54, 7, 47, 1, 'GET|PUT|DELETE'),
(55, 7, 45, 1, 'GET'),
(56, 8, 65, 1, 'POST|PUT'),
(57, 8, 47, 1, 'POST|PUT|DELETE'),
(58, 8, 46, 1, 'POST'),
(59, 1, 74, 1, 'DELETE'),
(60, 1, 72, 1, 'GET|POST|PUT|DELETE'),
(61, 1, 71, 1, 'GET|POST|PUT|DELETE'),
(62, 1, 70, 1, 'POST'),
(63, 1, 69, 1, 'GET'),
(64, 1, 68, 1, 'GET'),
(65, 1, 67, 1, 'GET'),
(66, 1, 66, 1, 'GET'),
(67, 1, 62, 1, 'GET'),
(68, 1, 61, 1, 'GET'),
(69, 1, 60, 1, 'GET'),
(70, 1, 58, 1, 'GET|POST'),
(71, 1, 57, 1, 'GET|POST|PUT|DELETE'),
(72, 1, 56, 1, 'GET|POST|PUT|DELETE'),
(73, 1, 55, 1, 'GET'),
(74, 1, 54, 1, 'GET|POST|PUT|DELETE'),
(75, 1, 49, 1, 'GET|POST|PUT|DELETE'),
(76, 1, 44, 1, 'GET|POST|PUT|DELETE'),
(77, 1, 43, 1, 'GET|POST|PUT|DELETE'),
(78, 1, 42, 1, 'GET|POST|PUT|DELETE'),
(79, 1, 15, 1, 'GET'),
(80, 1, 14, 1, 'GET'),
(81, 1, 11, 1, 'GET'),
(82, 1, 10, 1, 'GET'),
(83, 1, 9, 1, 'GET|POST|PUT|DELETE'),
(84, 1, 8, 1, 'GET'),
(85, 1, 7, 1, 'GET|POST|PUT|DELETE'),
(86, 1, 6, 1, 'GET|POST|PUT|DELETE'),
(87, 1, 5, 1, 'GET|POST|PUT|DELETE'),
(88, 1, 4, 1, 'GET|POST|PUT|DELETE'),
(89, 1, 3, 1, 'GET|POST|PUT|DELETE'),
(90, 1, 2, 1, 'GET|POST|PUT|DELETE');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_settings`
--

CREATE TABLE IF NOT EXISTS `fusio_settings` (
`id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_user`
--

CREATE TABLE IF NOT EXISTS `fusio_user` (
`id` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `name` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_user`
--

INSERT INTO `fusio_user` (`id`, `status`, `name`, `password`, `date`) VALUES
(1, 1, 'test', '$2y$10$XIjrxAWxA1/0sVcN9kXcfu9ev5IMWYdLxEJ.U6q12Q/Aiw2iH/qFK', '2015-02-27 19:59:15'),
(2, 2, 'adasd', '$2y$10$eRVK4R022UJ/ctgGLmOrI.PVlJvXcBjIgYaweF7Vwdw5Pl3Dc7w.m', '2015-03-01 10:37:33'),
(3, 0, 'asdasdas', '$2y$10$yFg3HHqL/P9GQnac53punem8ew4b.CcFdQZ7TOjZ.ZRKbKQzlxR56', '2015-03-01 10:37:44'),
(4, 0, 'foobar', '$2y$10$2AUp/zOm57qh/MFXfXYcJOY.IYjkmkqRRrSEf5AgCQ5iOGDWW2W3O', '2015-03-01 10:46:26'),
(9, 0, 'fsdfsdf', '$2y$10$jZAuKg.1otV6hVc.hkb7OOJimLVj11nLYLehU72I2FoZfr26rVZK2', '2015-03-07 09:14:44'),
(15, 0, 'fooooo', '$2y$10$NbXz3B2xsfF5BVDiByNDremlEqBK0y6LBms7uI18lj6xzy.1LpiVO', '2015-03-07 13:41:53'),
(16, 0, 'asdasd', '$2y$10$iMCbChWIOSOXn02Fsgij4uqHWM8gS2TDXv7YmNr3DznKpt2ShY0yy', '2015-03-07 14:00:30'),
(17, 0, 'sdfsdfsdf', '$2y$10$6BSQXZxIwZ7B4T5J.SfHUeS64D0A8NS3z8kZwU77k9qU12Ulg9Amm', '2015-03-07 14:02:01'),
(18, 0, 'asdad', '$2y$10$6y7MLoAQsUGls.Egs9GAkuwn.bbPC7xsJN9Bk3nMB/JqmihbtkBGO', '2015-04-23 18:42:55'),
(19, 0, 'sdfsdf', '$2y$10$IRrNnl2ShsgXDdBB/xPmKOG8yuHBsN1Qzlwe8LMjQ.WD2HrN2n8pC', '2015-04-23 18:46:40'),
(20, 0, 'dfsdfsdf', '$2y$10$wD0UBhNCxVzS5eq3hgfC7ullS16YLg7aqnnP9rncTeR0cArSrIar6', '2015-04-23 18:46:50'),
(22, 0, 'asdasdasdasd', '$2y$10$bUiR/R/RnPp5rzXgItagHuHhJNl8HCHEDwh2qvWe1tCgm2A9HUGTG', '2015-04-23 18:48:25'),
(24, 0, 'sdfsdfsadasd', '$2y$10$qeIX3IvXedTStbdPujgpguHS2x/mgYiJCeCTcWdfu8p9MD00CCx.m', '2015-05-05 18:06:50');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_user_scope`
--

CREATE TABLE IF NOT EXISTS `fusio_user_scope` (
`id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `scopeId` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_user_scope`
--

INSERT INTO `fusio_user_scope` (`id`, `userId`, `scopeId`) VALUES
(6, 15, 3),
(17, 17, 7),
(18, 17, 5),
(19, 16, 5),
(20, 16, 3),
(21, 18, 7),
(22, 18, 5),
(23, 19, 5),
(24, 19, 3),
(25, 20, 8),
(26, 22, 5),
(27, 22, 3),
(28, 24, 7),
(29, 24, 5);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `fusio_action`
--
ALTER TABLE `fusio_action`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `fusio_app`
--
ALTER TABLE `fusio_app`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD UNIQUE KEY `appKey` (`appKey`), ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `fusio_app_scope`
--
ALTER TABLE `fusio_app_scope`
 ADD PRIMARY KEY (`id`), ADD KEY `appId` (`appId`), ADD KEY `scopeId` (`scopeId`);

--
-- Indizes für die Tabelle `fusio_app_token`
--
ALTER TABLE `fusio_app_token`
 ADD PRIMARY KEY (`id`), ADD KEY `appId` (`appId`), ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `fusio_connection`
--
ALTER TABLE `fusio_connection`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `fusio_log`
--
ALTER TABLE `fusio_log`
 ADD PRIMARY KEY (`id`), ADD KEY `appId` (`appId`), ADD KEY `routeId` (`routeId`);

--
-- Indizes für die Tabelle `fusio_routes`
--
ALTER TABLE `fusio_routes`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `path` (`path`);

--
-- Indizes für die Tabelle `fusio_schema`
--
ALTER TABLE `fusio_schema`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD KEY `extendsId` (`extendsId`);

--
-- Indizes für die Tabelle `fusio_schema_fields`
--
ALTER TABLE `fusio_schema_fields`
 ADD PRIMARY KEY (`id`), ADD KEY `schemaId` (`schemaId`), ADD KEY `refId` (`refId`);

--
-- Indizes für die Tabelle `fusio_scope`
--
ALTER TABLE `fusio_scope`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `fusio_scope_routes`
--
ALTER TABLE `fusio_scope_routes`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `scopeRouteId` (`scopeId`,`routeId`), ADD KEY `routeId` (`routeId`), ADD KEY `scopeId` (`scopeId`);

--
-- Indizes für die Tabelle `fusio_settings`
--
ALTER TABLE `fusio_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_user`
--
ALTER TABLE `fusio_user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `fusio_user_scope`
--
ALTER TABLE `fusio_user_scope`
 ADD PRIMARY KEY (`id`), ADD KEY `userId` (`userId`), ADD KEY `scopeId` (`scopeId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `fusio_action`
--
ALTER TABLE `fusio_action`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT für Tabelle `fusio_app`
--
ALTER TABLE `fusio_app`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT für Tabelle `fusio_app_scope`
--
ALTER TABLE `fusio_app_scope`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT für Tabelle `fusio_app_token`
--
ALTER TABLE `fusio_app_token`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT für Tabelle `fusio_connection`
--
ALTER TABLE `fusio_connection`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `fusio_log`
--
ALTER TABLE `fusio_log`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=155;
--
-- AUTO_INCREMENT für Tabelle `fusio_routes`
--
ALTER TABLE `fusio_routes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT für Tabelle `fusio_schema`
--
ALTER TABLE `fusio_schema`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT für Tabelle `fusio_schema_fields`
--
ALTER TABLE `fusio_schema_fields`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT für Tabelle `fusio_scope`
--
ALTER TABLE `fusio_scope`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT für Tabelle `fusio_scope_routes`
--
ALTER TABLE `fusio_scope_routes`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT für Tabelle `fusio_settings`
--
ALTER TABLE `fusio_settings`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `fusio_user`
--
ALTER TABLE `fusio_user`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT für Tabelle `fusio_user_scope`
--
ALTER TABLE `fusio_user_scope`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `fusio_app`
--
ALTER TABLE `fusio_app`
ADD CONSTRAINT `appUserId` FOREIGN KEY (`userId`) REFERENCES `fusio_user` (`id`);

--
-- Constraints der Tabelle `fusio_app_scope`
--
ALTER TABLE `fusio_app_scope`
ADD CONSTRAINT `appScopeAppId` FOREIGN KEY (`appId`) REFERENCES `fusio_app` (`id`),
ADD CONSTRAINT `appScopeScopeId` FOREIGN KEY (`scopeId`) REFERENCES `fusio_scope` (`id`);

--
-- Constraints der Tabelle `fusio_app_token`
--
ALTER TABLE `fusio_app_token`
ADD CONSTRAINT `appTokenAppId` FOREIGN KEY (`appId`) REFERENCES `fusio_app` (`id`),
ADD CONSTRAINT `appTokenUserId` FOREIGN KEY (`userId`) REFERENCES `fusio_user` (`id`);

--
-- Constraints der Tabelle `fusio_log`
--
ALTER TABLE `fusio_log`
ADD CONSTRAINT `logAppId` FOREIGN KEY (`appId`) REFERENCES `fusio_app` (`id`),
ADD CONSTRAINT `logRouteId` FOREIGN KEY (`routeId`) REFERENCES `fusio_routes` (`id`);

--
-- Constraints der Tabelle `fusio_schema`
--
ALTER TABLE `fusio_schema`
ADD CONSTRAINT `schemaExtendsId` FOREIGN KEY (`extendsId`) REFERENCES `fusio_schema` (`id`);

--
-- Constraints der Tabelle `fusio_schema_fields`
--
ALTER TABLE `fusio_schema_fields`
ADD CONSTRAINT `schemaFieldsRefId` FOREIGN KEY (`refId`) REFERENCES `fusio_schema` (`id`),
ADD CONSTRAINT `schemaFieldsSchemaId` FOREIGN KEY (`schemaId`) REFERENCES `fusio_schema` (`id`);

--
-- Constraints der Tabelle `fusio_scope_routes`
--
ALTER TABLE `fusio_scope_routes`
ADD CONSTRAINT `scopeRoutesRouteId` FOREIGN KEY (`routeId`) REFERENCES `fusio_routes` (`id`),
ADD CONSTRAINT `scopeRoutesScopeId` FOREIGN KEY (`scopeId`) REFERENCES `fusio_scope` (`id`);

--
-- Constraints der Tabelle `fusio_user_scope`
--
ALTER TABLE `fusio_user_scope`
ADD CONSTRAINT `userScopeScopeId` FOREIGN KEY (`scopeId`) REFERENCES `fusio_scope` (`id`),
ADD CONSTRAINT `userScopeUserId` FOREIGN KEY (`userId`) REFERENCES `fusio_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
