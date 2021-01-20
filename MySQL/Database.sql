-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2019 at 08:08 PM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `remember_token`) VALUES
(1, 'admin', '$2y$10$hGg02aSLIpMkAAXngodN8Om2PeOw4jvNPKNP6AVZz3gPdmQ0I1Fx2', '');

-- --------------------------------------------------------

--
-- Table structure for table `info_pages`
--

CREATE TABLE `info_pages` (
  `id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `public` tinyint(4) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `info_pages`
--

INSERT INTO `info_pages` (`id`, `title`, `url`, `public`, `content`) VALUES
(1, 'Contact', 'contact', 1, 'Add your Contact information'),
(2, 'About', 'about', 1, 'Add your About information');

-- --------------------------------------------------------

--
-- Table structure for table `search_limit`
--

CREATE TABLE `search_limit` (
  `ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('ads_1', ''),
('ads_2', ''),
('ads_3', ''),
('ads_safe', '0'),
('favicon', 'favicon.png'),
('images_per_page', '100'),
('logo_large', 'logo_large.svg'),
('logo_large_dark', 'logo_large_dark.svg'),
('logo_small', 'logo_small.svg'),
('logo_small_dark', 'logo_small_dark.svg'),
('news_per_page', '10'),
('search_answers', '1'),
('search_api_key', ''),
('search_entities', '1'),
('search_highlight', 'false'),
('search_market', 'en-US'),
('search_new_window', '0'),
('search_per_ip', '100'),
('search_privacy', '0'),
('search_related', '1'),
('search_safe_search', 'Moderate'),
('search_sites', ''),
('search_suggestions', '1'),
('search_time', '86400'),
('site_backgrounds', '1'),
('site_center_content', '0'),
('site_dark_mode', '0'),
('site_language', 'english'),
('site_tagline', 'Lorem ipsum dolor sit amet'),
('site_theme', 'search'),
('site_title', 'phpSearch'),
('suggestions_per_ip', '300'),
('timezone', ''),
('tracking_code', ''),
('videos_per_page', '10'),
('web_per_page', '10');

-- --------------------------------------------------------

--
-- Table structure for table `suggestions_limit`
--

CREATE TABLE `suggestions_limit` (
  `ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info_pages`
--
ALTER TABLE `info_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_limit`
--
ALTER TABLE `search_limit`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `suggestions_limit`
--
ALTER TABLE `suggestions_limit`
  ADD PRIMARY KEY (`ip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `info_pages`
--
ALTER TABLE `info_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
