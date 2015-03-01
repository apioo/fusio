-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Mrz 2015 um 00:17
-- Server Version: 5.6.21
-- PHP-Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_action`
--

INSERT INTO `fusio_action` (`id`, `name`, `class`, `config`) VALUES
(6, 'actual query', 'Fusio\\Action\\SqlQuerySelect', 'a:3:{s:10:"connection";i:1;s:3:"sql";s:26:"SELECT * FROM fusio_routes";s:12:"propertyName";s:3:"foo";}'),
(7, 'static response', 'Fusio\\Action\\StaticResponse', 'a:1:{s:8:"response";s:118:"{\n    "foo": "bar", \n    "bar": "some",\n    "user": [{\n        "title": "news"\n    },{\n        "title": "foo"\n    }]\n}";}'),
(11, 'Hello world', 'Fusio\\Action\\StaticResponse', 'a:1:{s:8:"response";s:19:"{"Hello": "World!"}";}'),
(12, 'select all actions', 'Fusio\\Action\\SqlQuerySelect', 'a:2:{s:10:"connection";i:1;s:3:"sql";s:28:"SELECT * FROM `fusio_action`";}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app`
--

CREATE TABLE IF NOT EXISTS `fusio_app` (
`id` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `name` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `appKey` varchar(255) NOT NULL,
  `appSecret` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_app`
--

INSERT INTO `fusio_app` (`id`, `status`, `name`, `url`, `appKey`, `appSecret`, `date`) VALUES
(1, 2, 'Backend', 'http://fusio-project.org', '40d05050-52df-4982-9550-09f46522b73b', 'dede66744fffadc418f8aa87cb843f66004c070b200b2c224800d6d2f6b11e32', '0000-00-00 00:00:00'),
(2, 3, 'sdfsdf', 'sdfsdfsdf', '7f87de68-4545-4839-b11d-d80e59c147cb', '0725a5d774dda85e51c6b9e4e97cc6cf5480a9f1b7732ebc66c1a922cd3cb578', '0000-00-00 00:00:00'),
(3, 3, 'asdasdsad', 'asdasd', '0cdabe60-2fed-4b8d-8223-3a66ee39831b', '36abc2c9cba646782fc83c3117b709d773a671b180c5a8a6a926d85393e75cee', '2015-02-15 12:44:41'),
(4, 1, 'sadas', 'dasdasd', '58606036-5ba5-46d5-8112-25c2d12f132d', '9ecaf15df355ecac3face62f05633c3206879a2fddd92d74494e322c8f9ca946', '2015-02-15 12:44:58'),
(5, 1, 'werwerwer', 'werwerwer', '5347307d-d801-4075-9aaa-a21a29a448c5', '342cefac55939b31cd0a26733f9a4f061c0829ed87dae7caff50feaa55aff23d', '2015-02-22 22:19:07'),
(6, 1, 'Backend', 'http://fusio-project.org', '4ec4308c-d5d2-4564-8889-96d08c2541e7', 'a3093156eef1d410bfc235453a6ac0020718003d5c6d6b03721ab677ae9b6052', '2015-02-27 17:59:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app_scope`
--

CREATE TABLE IF NOT EXISTS `fusio_app_scope` (
`id` int(10) NOT NULL,
  `appId` int(10) NOT NULL,
  `scopeId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app_token`
--

CREATE TABLE IF NOT EXISTS `fusio_app_token` (
`id` int(10) NOT NULL,
  `appId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `token` varchar(512) NOT NULL,
  `scope` varchar(255) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `expire` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_app_token`
--

INSERT INTO `fusio_app_token` (`id`, `appId`, `userId`, `token`, `scope`, `ip`, `expire`, `date`) VALUES
(1, 1, 1, 'b88453bec3b2ebc8faeb2a330efbf56ed36253049061b689878d65120520ddec', 'backend', '127.0.0.1', 0, '2015-02-27 20:26:00'),
(2, 1, 1, 'f74191a1f0eb8e5c544c7b4ede7b4709bc778f4b31f05a40edbba3e30ac8db2d', 'backend', '127.0.0.1', 0, '2015-02-27 20:27:10'),
(3, 1, 1, 'ca4c93702b89ba5ef96ce6160b97f1c00f7f1e677490fac7b3be81203a93cba7', 'backend', '127.0.0.1', 1425068893, '2015-02-27 20:28:13'),
(4, 1, 1, '182579103084483e79f1791e8464086985861daa6b3062af899e9d321519ddee', 'backend', '127.0.0.1', 425068922, '2015-02-27 20:28:42'),
(5, 1, 1, 'df28d729af100b91195d7b7dede2d22c33f6f5f1e5b896fff3561d71a9d35079', 'backend', '127.0.0.1', 1425070231, '2015-02-27 20:50:31'),
(6, 1, 1, '2c999dc2e90e905b3dfdaf7a3590df69725d62893c2e4fb1acdebc45736e9e0f', 'backend', '127.0.0.1', 1425071256, '2015-02-27 21:07:36'),
(7, 1, 1, 'b04c6f936e387ba7f935cd8b7f6d98ea727db5f2177383df8f865d2bf14ae7f7', 'backend', '127.0.0.1', 1425071369, '2015-02-27 21:09:29'),
(8, 1, 1, 'af0131f6b43d85b13d578ffd085cc3203c4efe8979e4df9ffd6eaab455585bb2', 'backend', '127.0.0.1', 1425071633, '2015-02-27 21:13:53'),
(9, 1, 1, 'a2c2b0655232ba6e3a310bf14dceb49722d0e00f14dac6289c07a96de9390699', 'backend', '127.0.0.1', 1425073215, '2015-02-27 21:40:15'),
(10, 1, 1, '8532caca51c91aad66c2809eaabc0a72242473d8220baac67fe2b079366be205', 'backend', '127.0.0.1', 1425172386, '2015-02-28 20:13:06'),
(11, 1, 1, 'd75a293622840c074fbcce0724a6e4825bb96ebb82deb1b23daa0a81b3f9dad7', 'backend', '127.0.0.1', 1425172672, '2015-02-28 20:17:52'),
(12, 1, 1, '0417f2391ccc40abf54971b245202ebb09ba0db8c5d014160fd4f297b6911e54', 'backend', '127.0.0.1', 1425175043, '2015-02-28 20:57:23'),
(24, 1, 1, '70838f8ee5c5c78a60d33001c6c5480703d099ff20e00bbb62ac689fe9071e10', 'backend', '127.0.0.1', 1425186517, '2015-03-01 00:08:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_connection`
--

CREATE TABLE IF NOT EXISTS `fusio_connection` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `config` text
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_connection`
--

INSERT INTO `fusio_connection` (`id`, `name`, `class`, `config`) VALUES
(1, 'Native-Connection', 'Fusio\\Connection\\Native', NULL),
(2, 'dfsdfsdf', 'Fusio\\Connection\\DBAL', 'a:0:{}'),
(4, 'fail connection', 'Fusio\\Connection\\DBAL', 'a:5:{s:4:"type";s:9:"pdo_mysql";s:4:"host";s:6:"sdfsdf";s:8:"username";s:4:"sdfs";s:8:"password";s:8:"dfsdfsdf";s:8:"database";s:6:"sdfsdf";}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_log`
--

CREATE TABLE IF NOT EXISTS `fusio_log` (
`id` int(10) NOT NULL,
  `appId` int(10) NOT NULL,
  `routeId` int(10) DEFAULT NULL,
  `ip` varchar(40) NOT NULL,
  `method` varchar(16) NOT NULL,
  `path` varchar(255) NOT NULL,
  `header` text NOT NULL,
  `body` text,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_log`
--

INSERT INTO `fusio_log` (`id`, `appId`, `routeId`, `ip`, `method`, `path`, `header`, `body`, `date`) VALUES
(1, 0, 48, '127.0.0.1', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:08:21'),
(2, 0, 48, '127.0.0.1', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:08:22'),
(3, 0, 48, '127.0.0.1', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:08:22'),
(4, 0, 48, '127.0.0.1', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:20:54'),
(5, 0, 48, '127.0.0.1', 'GET', '/foo/barssss', 'a:7:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:20:54'),
(6, 0, 45, '127.0.0.1', 'GET', '/servers', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:44:22'),
(7, 0, 48, '127.0.0.1', 'GET', '/foo/barssss?format=xml', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:44:42'),
(8, 0, 59, '127.0.0.1', 'GET', '/all/actions', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-27 19:30:35'),
(9, 0, 59, '127.0.0.1', 'GET', '/all/actions?format=json', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-27 19:30:45'),
(10, 0, 59, '127.0.0.1', 'GET', '/all/actions', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:17'),
(11, 0, 59, '127.0.0.1', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:25'),
(12, 0, 59, '127.0.0.1', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:26'),
(13, 0, 59, '127.0.0.1', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:14:26'),
(14, 0, 59, '127.0.0.1', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:15:54'),
(15, 0, 59, '127.0.0.1', 'GET', '/all/actions', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:15:54'),
(16, 0, 48, '127.0.0.1', 'GET', '/foo/barssss', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:45'),
(17, 0, 63, '127.0.0.1', 'GET', '/foo', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:47'),
(18, 0, 63, '127.0.0.1', 'GET', '/foo', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:57'),
(19, 0, 63, '127.0.0.1', 'GET', '/foo', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:26:57'),
(20, 0, 64, '127.0.0.1', 'GET', '/bar/blub', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:27:29'),
(21, 0, 64, '127.0.0.1', 'GET', '/bar/blub', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:27:41'),
(22, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:31:05'),
(23, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:37'),
(24, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(25, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(26, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(27, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(28, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:38'),
(29, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(30, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(31, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(32, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(33, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(34, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(35, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:39'),
(36, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(37, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(38, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(39, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(40, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(41, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:40'),
(42, 0, 65, '127.0.0.1', 'GET', '/some/stuff', 'a:8:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:13:"cache-control";a:1:{i:0;s:9:"max-age=0";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:34:41'),
(43, 0, 65, '127.0.0.1', 'GET', '/some/stuff?foo=SELEC * FROM', 'a:7:{s:13:"authorization";a:1:{i:0;s:0:"";}s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-28 21:35:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

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
(52, 'GET', '/documentation', 'Fusio\\Controller\\DocumentationController::doIndex', NULL),
(53, 'GET', '/documentation/:version/*path', 'Fusio\\Controller\\DocumentationController::doDetail', NULL),
(54, 'GET|POST|PUT|DELETE', '/backend/app/:app_id', 'Fusio\\Backend\\Api\\App\\Entity', NULL),
(55, 'GET', '/backend/log/:log_id', 'Fusio\\Backend\\Api\\Log\\Entity', NULL),
(56, 'GET|POST|PUT|DELETE', '/backend/scope', 'Fusio\\Backend\\Api\\Scope\\Collection', NULL),
(57, 'GET|POST|PUT|DELETE', '/backend/scope/:scope_id', 'Fusio\\Backend\\Api\\Scope\\Entity', NULL),
(58, 'GET|POST', '/backend/token', 'Fusio\\Backend\\Api\\Authorization\\Token', NULL),
(59, 'GET', '/all/actions', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:12;}}}}'),
(60, 'GET', '/backend/whoami', 'Fusio\\Backend\\Api\\Authorization\\Whoami', NULL),
(61, 'GET', '/backend/dashboard/incoming_requests', 'Fusio\\Backend\\Api\\Dashboard\\IncomingRequests', NULL),
(62, 'GET', '/backend/dashboard/most_used_routes', 'Fusio\\Backend\\Api\\Dashboard\\MostUsedRoutes', NULL),
(63, 'GET', '/foo', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:11;}}}}'),
(64, 'GET', '/bar/blub', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":126:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:6;}}}}'),
(65, 'GET', '/some/stuff', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:11;}}}}'),
(66, 'GET', '/backend/dashboard/most_used_apps', 'Fusio\\Backend\\Api\\Dashboard\\MostUsedApps', NULL),
(67, 'GET', '/backend/dashboard/latest_requests', 'Fusio\\Backend\\Api\\Dashboard\\LatestRequests', NULL),
(68, 'GET', '/backend/dashboard/latest_apps', 'Fusio\\Backend\\Api\\Dashboard\\LatestApps', NULL),
(69, 'GET', '/backend/dashboard/latest_users', 'Fusio\\Backend\\Api\\Dashboard\\LatestUsers', NULL),
(70, 'POST', '/backend/revoke', 'Fusio\\Backend\\Api\\Authorization\\Revoke', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_schema`
--

CREATE TABLE IF NOT EXISTS `fusio_schema` (
`id` int(10) NOT NULL,
  `extendsId` int(10) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `propertyName` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_schema`
--

INSERT INTO `fusio_schema` (`id`, `extendsId`, `name`, `propertyName`) VALUES
(1, NULL, 'Passthru', 'passthru'),
(5, NULL, 'routes', 'route'),
(6, NULL, 'route collection', 'collection');

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_schema_fields`
--

INSERT INTO `fusio_schema_fields` (`id`, `schemaId`, `refId`, `name`, `type`, `required`, `min`, `max`, `pattern`, `enumeration`) VALUES
(15, 5, NULL, 'path', 'String', 0, NULL, NULL, NULL, NULL),
(16, 5, NULL, 'methods', 'String', 0, NULL, NULL, NULL, NULL),
(23, 6, 5, 'foo', 'Array', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_scope`
--

CREATE TABLE IF NOT EXISTS `fusio_scope` (
`id` int(10) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_scope`
--

INSERT INTO `fusio_scope` (`id`, `name`) VALUES
(3, 'foobar'),
(5, 'sdfsdfsdf'),
(6, 'sdfsdfsdf');

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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_scope_routes`
--

INSERT INTO `fusio_scope_routes` (`id`, `scopeId`, `routeId`, `allow`, `methods`) VALUES
(2, 2, 47, 1, 'POST|PUT'),
(39, 3, 48, 1, 'GET'),
(40, 3, 47, 1, 'POST|PUT'),
(41, 3, 46, 1, ''),
(43, 5, 48, 1, 'GET'),
(44, 5, 45, 1, 'GET'),
(45, 6, 48, 1, 'GET'),
(46, 6, 45, 1, 'GET');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_user`
--

INSERT INTO `fusio_user` (`id`, `status`, `name`, `password`, `date`) VALUES
(1, 1, 'test', '$2y$10$XIjrxAWxA1/0sVcN9kXcfu9ev5IMWYdLxEJ.U6q12Q/Aiw2iH/qFK', '2015-02-27 19:59:15');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `fusio_action`
--
ALTER TABLE `fusio_action`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_app`
--
ALTER TABLE `fusio_app`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_app_scope`
--
ALTER TABLE `fusio_app_scope`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_app_token`
--
ALTER TABLE `fusio_app_token`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_connection`
--
ALTER TABLE `fusio_connection`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_log`
--
ALTER TABLE `fusio_log`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_routes`
--
ALTER TABLE `fusio_routes`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_schema`
--
ALTER TABLE `fusio_schema`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_schema_fields`
--
ALTER TABLE `fusio_schema_fields`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_scope`
--
ALTER TABLE `fusio_scope`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_scope_routes`
--
ALTER TABLE `fusio_scope_routes`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_settings`
--
ALTER TABLE `fusio_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_user`
--
ALTER TABLE `fusio_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `fusio_action`
--
ALTER TABLE `fusio_action`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT für Tabelle `fusio_app`
--
ALTER TABLE `fusio_app`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `fusio_app_scope`
--
ALTER TABLE `fusio_app_scope`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `fusio_app_token`
--
ALTER TABLE `fusio_app_token`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT für Tabelle `fusio_connection`
--
ALTER TABLE `fusio_connection`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `fusio_log`
--
ALTER TABLE `fusio_log`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT für Tabelle `fusio_routes`
--
ALTER TABLE `fusio_routes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT für Tabelle `fusio_schema`
--
ALTER TABLE `fusio_schema`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `fusio_schema_fields`
--
ALTER TABLE `fusio_schema_fields`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT für Tabelle `fusio_scope`
--
ALTER TABLE `fusio_scope`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `fusio_scope_routes`
--
ALTER TABLE `fusio_scope_routes`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT für Tabelle `fusio_settings`
--
ALTER TABLE `fusio_settings`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `fusio_user`
--
ALTER TABLE `fusio_user`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;