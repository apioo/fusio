-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Feb 2015 um 16:46
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_action`
--

INSERT INTO `fusio_action` (`id`, `name`, `class`, `config`) VALUES
(6, 'actual query', 'Fusio\\Action\\SqlQuerySelect', 'a:3:{s:10:"connection";i:1;s:3:"sql";s:26:"SELECT * FROM fusio_routes";s:12:"propertyName";s:3:"foo";}'),
(7, 'static response', 'Fusio\\Action\\StaticResponse', 'a:1:{s:8:"response";s:118:"{\n    "foo": "bar", \n    "bar": "some",\n    "user": [{\n        "title": "news"\n    },{\n        "title": "foo"\n    }]\n}";}'),
(11, 'Hello world', 'Fusio\\Action\\StaticResponse', 'a:1:{s:8:"response";s:19:"{"Hello": "World!"}";}');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fusio_app`
--

INSERT INTO `fusio_app` (`id`, `status`, `name`, `url`, `appKey`, `appSecret`, `date`) VALUES
(1, 2, 'dsfs', 'dfdsf', '40d05050-52df-4982-9550-09f46522b73b', 'dede66744fffadc418f8aa87cb843f66004c070b200b2c224800d6d2f6b11e32', '0000-00-00 00:00:00'),
(2, 3, 'sdfsdf', 'sdfsdfsdf', '7f87de68-4545-4839-b11d-d80e59c147cb', '0725a5d774dda85e51c6b9e4e97cc6cf5480a9f1b7732ebc66c1a922cd3cb578', '0000-00-00 00:00:00'),
(3, 3, 'asdasdsad', 'asdasd', '0cdabe60-2fed-4b8d-8223-3a66ee39831b', '36abc2c9cba646782fc83c3117b709d773a671b180c5a8a6a926d85393e75cee', '2015-02-15 12:44:41'),
(4, 1, 'sadas', 'dasdasd', '58606036-5ba5-46d5-8112-25c2d12f132d', '9ecaf15df355ecac3face62f05633c3206879a2fddd92d74494e322c8f9ca946', '2015-02-15 12:44:58');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

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
(7, 0, 48, '127.0.0.1', 'GET', '/foo/barssss?format=xml', 'a:6:{s:4:"host";a:1:{i:0;s:9:"127.0.0.1";}s:10:"connection";a:1:{i:0;s:10:"keep-alive";}s:6:"accept";a:1:{i:0;s:74:"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";}s:10:"user-agent";a:1:{i:0;s:109:"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";}s:15:"accept-encoding";a:1:{i:0;s:19:"gzip, deflate, sdch";}s:15:"accept-language";a:1:{i:0;s:35:"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4";}}', NULL, '2015-02-15 16:44:42');

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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

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
(55, 'GET', '/backend/log/:log_id', 'Fusio\\Backend\\Api\\Log\\Entity', NULL);

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
  `name` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT für Tabelle `fusio_app`
--
ALTER TABLE `fusio_app`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `fusio_connection`
--
ALTER TABLE `fusio_connection`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `fusio_log`
--
ALTER TABLE `fusio_log`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `fusio_routes`
--
ALTER TABLE `fusio_routes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
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
-- AUTO_INCREMENT für Tabelle `fusio_settings`
--
ALTER TABLE `fusio_settings`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `fusio_user`
--
ALTER TABLE `fusio_user`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;