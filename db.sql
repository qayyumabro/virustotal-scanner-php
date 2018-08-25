-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2018 at 04:41 PM
-- Server version: 5.6.39
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `standalo_vtscans`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `{{prefix}}files` (
  `id` int(11) NOT NULL,
  `filehash` varchar(20) NOT NULL,
  `sha256` varchar(100) NOT NULL,
  `filename` text NOT NULL,
  `filesize` bigint(20) NOT NULL,
  `mime` varchar(50) NOT NULL,
  `url` text NOT NULL,
  `notes` text,
  `status` enum('Not Scanned','Queued','Skip','Oversize','Error','Completed') NOT NULL DEFAULT 'Not Scanned',
  `lastscantime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scans`
--

CREATE TABLE `{{prefix}}scans` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `positives` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `permalink` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `{{prefix}}settings` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `timezone` varchar(100) NOT NULL DEFAULT 'Asia/Karachi',
  `virustotalApiKey` text NOT NULL,
  `perPageRecords` int(11) NOT NULL DEFAULT '10',
  `maxFileUploads` int(11) NOT NULL DEFAULT '10',
  `vtScannerThreshold` int(11) NOT NULL DEFAULT '2',
  `vtReporterThreshold` int(11) NOT NULL DEFAULT '2',
  `emailNotify` tinyint(4) NOT NULL DEFAULT '0',
  `emailEmail` text NOT NULL,
  `emailPassword` text NOT NULL,
  `stmp` text NOT NULL,
  `port` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `{{prefix}}settings` (`id`, `email`, `password`, `timezone`, `virustotalApiKey`, `perPageRecords`, `maxFileUploads`, `vtScannerThreshold`, `vtReporterThreshold`, `emailNotify`, `emailEmail`, `emailPassword`, `stmp`, `port`, `datetime`) VALUES
(1, 'admin@admin.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Asia/Karachi', '', 10, 10, 2, 2, 0, '', '', '', 0, '2018-07-31 13:13:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `{{prefix}}files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scans`
--
ALTER TABLE `{{prefix}}scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `{{prefix}}settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `{{prefix}}files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scans`
--
ALTER TABLE `{{prefix}}scans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `{{prefix}}settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
