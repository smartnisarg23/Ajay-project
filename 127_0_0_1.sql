-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2014 at 02:34 PM
-- Server version: 5.6.17-log
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ron_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_master`
--

CREATE TABLE IF NOT EXISTS `company_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `web_url` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` bigint(20) NOT NULL,
  `phone_1` bigint(20) NOT NULL,
  `phone_2` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '-1 = Deleted, 0 = Inactive, 1= Active',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `company_master`
--

INSERT INTO `company_master` (`id`, `company_name`, `web_url`, `address_1`, `address_2`, `city`, `state`, `zipcode`, `phone_1`, `phone_2`, `status`, `created`, `modified`) VALUES
(1, 'test', '', '123 ,test avenue', 'grant road', 'texas', 'texas', 34123, 12345, 12345, 1, '2014-10-22 15:01:26', '2014-10-22 15:01:26'),
(2, 'Hericon', '', '123 ,test avenue', 'grant road', 'texas', 'texas', 34123, 12345, 0, 1, '2014-10-23 14:42:32', '2014-10-23 14:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `employee_details`
--

CREATE TABLE IF NOT EXISTS `employee_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` bigint(10) NOT NULL,
  `phone_1` bigint(10) NOT NULL,
  `phone_2` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

CREATE TABLE IF NOT EXISTS `role_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `role_master`
--

INSERT INTO `role_master` (`id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Company Admin'),
(3, 'Vendor');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `phone_1` bigint(20) NOT NULL,
  `phone_2` bigint(20) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `address_1`, `address_2`, `city`, `state`, `zipcode`, `phone_1`, `phone_2`, `profile_image`) VALUES
(4, 11, '123 ,test avenue', 'grant road', 'texas', 'texas', 34123, 12345, 12345, 'Lighthouse.jpg'),
(5, 12, '32, Satyadev Soc', 'kadi', 'Kadi', 'GUJ', 382715, 9979202310, 123456789, 'Jellyfish.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '-1 = Deleted, 0 = Inactive, 1= Active',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `company_id`, `username`, `email`, `password`, `firstname`, `lastname`, `status`, `created`, `modified`) VALUES
(1, 1, 0, 'superadmin', 'superadmin@crm.com', '1fc39f7fe988ce3ce75a1a49df34eec8', 'Super', 'Admin', 1, '2014-10-18 15:58:07', '2014-10-18 15:58:07'),
(11, 2, 2, 'nisargpatel', 'nisarg@gmail.com', 'be614a772d61dc5a', 'Nisarg', 'Patel', 1, '2014-10-23 12:41:54', '2014-10-23 16:11:54'),
(12, 1, 2, 'nisarg.phpdeveloper@gmail.com', 'nisarg.phpdeveloper@gmail.com', 'cf69a816a55547d5d881b8677b181780', 'Nisarg', 'Patel', 1, '2014-10-23 14:30:32', '2014-10-23 18:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_details`
--

CREATE TABLE IF NOT EXISTS `vendor_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vandor_name` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` bigint(20) NOT NULL,
  `phone_1` bigint(20) NOT NULL,
  `phone_2` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
