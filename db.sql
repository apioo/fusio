-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Jan 2015 um 19:36
-- Server Version: 5.6.16
-- PHP-Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `fusio`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_connection`
--

CREATE TABLE IF NOT EXISTS `fusio_connection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('MYSQL','MSSQL','MONGODB') NOT NULL,
  `name` varchar(64) NOT NULL,
  `parameter` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_controller`
--

CREATE TABLE IF NOT EXISTS `fusio_controller` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_routes`
--

CREATE TABLE IF NOT EXISTS `fusio_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `methods` varchar(64) NOT NULL,
  `path` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Daten für Tabelle `fusio_routes`
--

INSERT INTO `fusio_routes` (`id`, `methods`, `path`, `controller`) VALUES
(1, 'GET|POST|PUT|DELETE', '/backend', 'Fusio\\Backend\\Application\\Index'),
(2, 'GET|POST|PUT|DELETE', '/backend/routes', 'Fusio\\Backend\\Api\\Routes'),
(3, 'GET|POST|PUT|DELETE', '/backend/schema', 'Fusio\\Backend\\Api\\Schema'),
(4, 'GET|POST|PUT|DELETE', '/backend/controller', 'Fusio\\Backend\\Api\\Controller'),
(5, 'GET|POST|PUT|DELETE', '/backend/trigger', 'Fusio\\Backend\\Api\\Trigger'),
(6, 'GET|POST|PUT|DELETE', '/backend/connection', 'Fusio\\Backend\\Api\\Connection'),
(7, 'GET|POST|PUT|DELETE', '/backend/app', 'Fusio\\Backend\\Api\\App'),
(8, 'GET|POST|PUT|DELETE', '/backend/user', 'Fusio\\Backend\\Api\\User'),
(9, 'GET|POST|PUT|DELETE', '/backend/settings', 'Fusio\\Backend\\Api\\Settings'),
(10, 'GET|POST|PUT|DELETE', '/backend/log', 'Fusio\\Backend\\Api\\Log'),
(35, 'asdasd', 'asd', 'asdasd'),
(36, 'asdasda', 'asdasd', 'asdasd'),
(37, '/foo/bar/some', 'sadasd', 'asdasdasd'),
(38, '/foo/bar', 'asdasd', 'asdadasd'),
(39, 'GET|POST', '/foo/bar/test', 'Fusio\\Backend\\Test\\Bar'),
(40, 'PUT|DELET', '/foo/bar', 'some contetn'),
(41, 'GET|POST', '/foo/bar/test', 'some content');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fusio_schema`
--

CREATE TABLE IF NOT EXISTS `fusio_schema` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
