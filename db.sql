-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Feb 2015 um 20:11
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
(4, 'sdfsdf', 'Fusio\\Connection\\DBAL', 'a:5:{s:4:"type";s:9:"pdo_mysql";s:4:"host";s:6:"sdfsdf";s:8:"username";s:4:"sdfs";s:8:"password";s:8:"dfsdfsdf";s:8:"database";s:6:"sdfsdf";}');

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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

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
(7, 'GET|POST|PUT|DELETE', '/backend/app', 'Fusio\\Backend\\Api\\App', NULL),
(8, 'GET', '/backend/action/list', 'Fusio\\Backend\\Api\\Action\\ListActions::doIndex', NULL),
(9, 'GET|POST|PUT|DELETE', '/backend/settings', 'Fusio\\Backend\\Api\\Settings', NULL),
(10, 'GET|POST|PUT|DELETE', '/backend/log', 'Fusio\\Backend\\Api\\Log', NULL),
(11, 'GET', '/backend/action/form', 'Fusio\\Backend\\Api\\Action\\ListActions::doDetail', NULL),
(14, 'GET', '/backend/connection/form', 'Fusio\\Backend\\Api\\Connection\\ListConnections::doDetail', NULL),
(15, 'GET', '/backend/connection/list', 'Fusio\\Backend\\Api\\Connection\\ListConnections::doIndex', NULL),
(42, 'GET|POST|PUT|DELETE', '/backend/routes/:route_id', 'Fusio\\Backend\\Api\\Routes\\Entity', NULL),
(43, 'GET|POST|PUT|DELETE', '/backend/action/:action_id', 'Fusio\\Backend\\Api\\Action\\Entity', NULL),
(44, 'GET|POST|PUT|DELETE', '/backend/schema/:schema_id', 'Fusio\\Backend\\Api\\Schema\\Entity', NULL),
(45, 'GET', '/servers', 'Fusio\\Controller\\SchemaApiController', NULL),
(46, 'POST', '/server/reboot', 'Fusio\\Controller\\SchemaApiController', NULL),
(47, 'GET|POST|PUT|DELETE', '/servser/config', 'Fusio\\Controller\\SchemaApiController', NULL),
(48, 'GET', '/foo/barssss', 'Fusio\\Controller\\SchemaApiController', 'a:1:{i:0;C:15:"PSX\\Data\\Record":127:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"method";s:3:"GET";s:7:"request";i:0;s:8:"response";i:1;s:6:"action";i:11;}}}}'),
(49, 'GET|POST|PUT|DELETE', '/backend/connection/:connection_id', 'Fusio\\Backend\\Api\\Connection\\Entity', NULL);

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
(4, NULL, 'utzutzu', ''),
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
(8, 4, NULL, 'tzutz', 'String', 0, NULL, NULL, NULL, NULL),
(9, 4, NULL, 'tzutz', 'String', 0, NULL, NULL, NULL, NULL),
(10, 4, NULL, 'tuz', 'String', 0, NULL, NULL, NULL, NULL),
(11, 4, NULL, 'tzuztu', 'String', 0, NULL, NULL, NULL, NULL),
(15, 5, NULL, 'path', 'String', 0, NULL, NULL, NULL, NULL),
(16, 5, NULL, 'methods', 'String', 0, NULL, NULL, NULL, NULL),
(23, 6, 5, 'foo', 'Array', 0, NULL, NULL, NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `fusio_action`
--
ALTER TABLE `fusio_action`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fusio_connection`
--
ALTER TABLE `fusio_connection`
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
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `fusio_action`
--
ALTER TABLE `fusio_action`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT für Tabelle `fusio_connection`
--
ALTER TABLE `fusio_connection`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `fusio_routes`
--
ALTER TABLE `fusio_routes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
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
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

