-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 24. November 2013 um 14:41
-- Server Version: 5.5.8
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `fusio`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_api`
--

CREATE TABLE IF NOT EXISTS `fusio_api` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `datasourceId` int(10) NOT NULL,
  `allowedMethods` set('GET','POST','PUT','DELETE') NOT NULL DEFAULT 'GET',
  `path` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `getLimit` int(10) DEFAULT NULL,
  `postLimit` int(10) DEFAULT NULL,
  `putLimit` int(10) DEFAULT NULL,
  `deleteLimit` int(10) DEFAULT NULL,
  `limitInterval` enum('YEAR','MONTH','WEEK','DAY','HOUR') NOT NULL DEFAULT 'HOUR',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `fusio_api`
--

INSERT INTO `fusio_api` (`id`, `datasourceId`, `allowedMethods`, `path`, `description`, `getLimit`, `postLimit`, `putLimit`, `deleteLimit`, `limitInterval`) VALUES
(1, 1, 'GET,POST,PUT,DELETE', '/foo', 'Some foo api with a description', NULL, NULL, NULL, NULL, 'HOUR'),
(2, 1, 'GET', '/bar', 'Another bar API wich can some magic too', NULL, NULL, NULL, NULL, 'HOUR');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_api_field`
--

CREATE TABLE IF NOT EXISTS `fusio_api_field` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `apiId` int(10) NOT NULL,
  `objectId` int(10) DEFAULT NULL,
  `method` enum('GET','POST','PUT','DELETE') NOT NULL,
  `sort` int(10) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `fusio_api_field`
--

INSERT INTO `fusio_api_field` (`id`, `apiId`, `objectId`, `method`, `sort`, `name`, `description`, `required`) VALUES
(1, 1, NULL, 'GET', 0, 'name', 'name and so on', 1),
(2, 1, NULL, 'GET', 1, 'comment', 'and a kewl comment and so on', 0),
(3, 1, 1, 'GET', 1, 'date', 'and a kewl comment and so on', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_api_filter`
--

CREATE TABLE IF NOT EXISTS `fusio_api_filter` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fieldId` int(10) NOT NULL,
  `filterId` int(10) NOT NULL,
  `sort` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_api_filter`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_api_trigger`
--

CREATE TABLE IF NOT EXISTS `fusio_api_trigger` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `apiId` int(10) NOT NULL,
  `triggerId` int(10) NOT NULL,
  `allowedMethods` set('POST','PUT','DELETE') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_api_trigger`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app`
--

CREATE TABLE IF NOT EXISTS `fusio_app` (
  `appId` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`appId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_app`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app_access`
--

CREATE TABLE IF NOT EXISTS `fusio_app_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `apiUserId` (`appId`,`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fusio_app_access`
--

INSERT INTO `fusio_app_access` (`id`, `appId`, `userId`, `allowed`, `date`) VALUES
(1, 1, 1, 1, '2013-11-17 17:58:16');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_app_request`
--

CREATE TABLE IF NOT EXISTS `fusio_app_request` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `nonce` varchar(16) NOT NULL,
  `callback` varchar(255) NOT NULL,
  `token` varchar(40) NOT NULL,
  `tokenSecret` varchar(40) NOT NULL,
  `verifier` varchar(32) NOT NULL,
  `timestamp` varchar(25) NOT NULL,
  `expire` varchar(25) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM  DEFAULT CHARSET=ascii AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fusio_app_request`
--

INSERT INTO `fusio_app_request` (`id`, `appId`, `userId`, `status`, `ip`, `nonce`, `callback`, `token`, `tokenSecret`, `verifier`, `timestamp`, `expire`, `date`) VALUES
(1, 1, 1, 3, '127.0.0.1', 'ec06de5065966fd9', 'oob', '9a9799327101c9bd33070bc4ae5c7b96230409ce', '18bed3c38ede2a1761a5d468cf4993d7b770f7d5', '83f0608bef7605bf8badd4dd821a3caf', '1384711093', 'P6M', '2013-11-17 17:58:19');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_datasource`
--

CREATE TABLE IF NOT EXISTS `fusio_datasource` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('MYSQL','HTTP') NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fusio_datasource`
--

INSERT INTO `fusio_datasource` (`id`, `type`, `params`) VALUES
(1, 'MYSQL', 'foo');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_filter`
--

CREATE TABLE IF NOT EXISTS `fusio_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('LENGTH','EMAIL','IP') NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_filter`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_log`
--

CREATE TABLE IF NOT EXISTS `fusio_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `apiId` int(10) NOT NULL,
  `appId` int(10) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `level` enum('INFO','WARNING') NOT NULL,
  `message` varchar(128) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_log`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_object`
--

CREATE TABLE IF NOT EXISTS `fusio_object` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `fusio_object`
--

INSERT INTO `fusio_object` (`id`, `name`, `description`) VALUES
(1, 'DateTime', 'The value should be an <a href="http://tools.ietf.org/html/rfc3339#section-5.6">RFC3339</a> formatted date string'),
(2, 'String', ''),
(3, 'Integer', ''),
(4, 'Float', ''),
(5, 'Boolean', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_object_field`
--

CREATE TABLE IF NOT EXISTS `fusio_object_field` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `objectId` int(10) NOT NULL,
  `objectRefId` int(10) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_object_field`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_trigger`
--

CREATE TABLE IF NOT EXISTS `fusio_trigger` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('WEBHOOK','MESSAGEQUEUE','EMAIL') NOT NULL,
  `name` varchar(32) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_trigger`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_user`
--

CREATE TABLE IF NOT EXISTS `fusio_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `pw` varchar(40) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `fusio_user`
--

