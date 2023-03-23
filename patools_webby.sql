-- MariaDB dump 10.19  Distrib 10.5.15-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: patools_pfu
-- ------------------------------------------------------
-- Server version	10.5.15-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_user_id_index` (`user_id`),
  KEY `activity_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=281654 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `advanced_unit_scans`
--

DROP TABLE IF EXISTS `advanced_unit_scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advanced_unit_scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) NOT NULL,
  `ship_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alliance_history`
--

DROP TABLE IF EXISTS `alliance_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alliance_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rank` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `members` int(11) NOT NULL,
  `counted_score` bigint(20) NOT NULL,
  `points` bigint(20) NOT NULL,
  `total_score` bigint(20) NOT NULL,
  `total_value` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `alliance_id` int(11) NOT NULL DEFAULT 0,
  `avg_size` bigint(20) NOT NULL,
  `avg_value` bigint(20) NOT NULL,
  `avg_score` bigint(20) NOT NULL,
  `change_value` bigint(20) NOT NULL DEFAULT 0,
  `change_score` bigint(20) NOT NULL DEFAULT 0,
  `change_xp` bigint(20) NOT NULL DEFAULT 0,
  `change_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `change_members` int(11) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alliances`
--

DROP TABLE IF EXISTS `alliances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alliances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hidden_resources` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `prod_resources` bigint(20) NOT NULL DEFAULT 0,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `score` bigint(20) NOT NULL DEFAULT 0,
  `value` bigint(20) NOT NULL DEFAULT 0,
  `avg_size` bigint(20) NOT NULL DEFAULT 0,
  `avg_value` bigint(20) NOT NULL DEFAULT 0,
  `avg_score` bigint(20) NOT NULL DEFAULT 0,
  `members` int(11) NOT NULL DEFAULT 0,
  `total_score` bigint(20) NOT NULL DEFAULT 0,
  `day_rank_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_size` int(11) NOT NULL DEFAULT 0,
  `day_rank_avg_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_avg_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_avg_size` int(11) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `day_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `day_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_rank_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_size` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_avg_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_avg_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_avg_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_rank_avg_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_avg_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_avg_size` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_avg_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_avg_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_avg_size` double(8,2) NOT NULL DEFAULT 0.00,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relation` enum('neutral','friendly','hostile') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_locked` int(11) NOT NULL DEFAULT 0,
  `day_members` int(11) NOT NULL DEFAULT 0,
  `growth_members` int(11) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `size_diff` bigint(20) NOT NULL DEFAULT 0,
  `xp_diff` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attack_booking_battlegroup`
--

DROP TABLE IF EXISTS `attack_booking_battlegroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attack_booking_battlegroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `battlegroup_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `teamup_id` int(11) DEFAULT NULL,
  `attack_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attack_booking_users`
--

DROP TABLE IF EXISTS `attack_booking_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attack_booking_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attack_booking_users_booking_id_index` (`booking_id`),
  KEY `attack_booking_users_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attack_bookings`
--

DROP TABLE IF EXISTS `attack_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attack_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attack_id` int(11) NOT NULL,
  `attack_target_id` int(11) NOT NULL,
  `land_tick` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `battle_calc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attack_bookings_attack_id_index` (`attack_id`),
  KEY `attack_bookings_attack_target_id_index` (`attack_target_id`),
  KEY `attack_bookings_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attack_targets`
--

DROP TABLE IF EXISTS `attack_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attack_targets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attack_id` int(11) NOT NULL,
  `planet_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attack_targets_attack_id_index` (`attack_id`),
  KEY `attack_targets_planet_id_index` (`planet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attacks`
--

DROP TABLE IF EXISTS `attacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attacks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `land_tick` int(11) NOT NULL,
  `waves` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `scheduled_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_opened` tinyint(1) NOT NULL DEFAULT 1,
  `attack_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prerelease_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_prereleased` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attacks_attack_id_index` (`attack_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `battlegroup_fleets`
--

DROP TABLE IF EXISTS `battlegroup_fleets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `battlegroup_fleets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fleet_id` int(11) NOT NULL,
  `ship_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `battlegroup_id` int(11) DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `battlegroup_teamups`
--

DROP TABLE IF EXISTS `battlegroup_teamups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `battlegroup_teamups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `fleet_id` int(11) DEFAULT NULL,
  `teamup_id` int(11) DEFAULT NULL,
  `battlegroup_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `battlegroup_users`
--

DROP TABLE IF EXISTS `battlegroup_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `battlegroup_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `battlegroup_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_pending` int(11) NOT NULL DEFAULT 1,
  `fleet_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `battlegroups`
--

DROP TABLE IF EXISTS `battlegroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `battlegroups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator_id` int(11) NOT NULL,
  `value` bigint(20) NOT NULL DEFAULT 0,
  `score` bigint(20) NOT NULL DEFAULT 0,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_xp` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_xp` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `botan_shortener`
--

DROP TABLE IF EXISTS `botan_shortener`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `botan_shortener` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_url` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `botan_shortener_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `callback_query`
--

DROP TABLE IF EXISTS `callback_query`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `callback_query` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique identifier for this query',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Unique message identifier',
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `chat_id_2` (`chat_id`,`message_id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `message_id` (`message_id`),
  CONSTRAINT `callback_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `callback_query_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `message` (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` bigint(20) NOT NULL COMMENT 'Unique user or chat identifier',
  `type` enum('private','group','supergroup','channel') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Chat type, either private, group, supergroup or channel',
  `title` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `all_members_are_administrators` tinyint(1) DEFAULT 0 COMMENT 'True if a all members of this group are admins',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `old_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier, this is filled when a group is converted to a supergroup',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `old_id` (`old_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chosen_inline_result`
--

DROP TABLE IF EXISTS `chosen_inline_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chosen_inline_result` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `result_id` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `location` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `query` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `chosen_inline_result_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conversation`
--

DROP TABLE IF EXISTS `conversation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique user or chat identifier',
  `status` enum('active','cancelled','stopped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Conversation state',
  `command` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `status` (`status`),
  CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cookies`
--

DROP TABLE IF EXISTS `cookies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `development_scans`
--

DROP TABLE IF EXISTS `development_scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `development_scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) NOT NULL,
  `light_factory` int(11) NOT NULL,
  `medium_factory` int(11) NOT NULL,
  `heavy_factory` int(11) NOT NULL,
  `wave_amplifier` int(11) NOT NULL,
  `wave_distorter` int(11) NOT NULL,
  `metal_refinery` int(11) NOT NULL,
  `crystal_refinery` int(11) NOT NULL,
  `eonium_refinery` int(11) NOT NULL,
  `research_lab` int(11) NOT NULL,
  `finance_centre` int(11) NOT NULL,
  `security_centre` int(11) NOT NULL,
  `military_centre` int(11) NOT NULL,
  `structure_defence` int(11) NOT NULL,
  `travel` int(11) NOT NULL,
  `infrastructure` int(11) NOT NULL,
  `hulls` int(11) NOT NULL,
  `waves` int(11) NOT NULL,
  `core` int(11) NOT NULL,
  `covert_op` int(11) NOT NULL,
  `mining` int(11) NOT NULL,
  `population` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edited_message`
--

DROP TABLE IF EXISTS `edited_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edited_message` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Unique message identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `edit_date` datetime DEFAULT NULL COMMENT 'Date the message was edited in timestamp format',
  `text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_id_2` (`chat_id`,`message_id`),
  KEY `chat_id` (`chat_id`),
  KEY `message_id` (`message_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `edited_message_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  CONSTRAINT `edited_message_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `message` (`chat_id`),
  CONSTRAINT `edited_message_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3746 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fleet_movements`
--

DROP TABLE IF EXISTS `fleet_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fleet_movements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `launch_tick` int(11) DEFAULT NULL,
  `fleet_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planet_from_id` int(11) NOT NULL,
  `planet_to_id` int(11) NOT NULL,
  `mission_type` enum('Attack','Defend') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `land_tick` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `eta` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ship_count` bigint(20) DEFAULT NULL,
  `source` enum('incoming','launch','jgp','parser','notification') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `galaxies`
--

DROP TABLE IF EXISTS `galaxies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galaxies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hidden_resources` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `prod_resources` bigint(20) NOT NULL DEFAULT 0,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `score` bigint(20) NOT NULL DEFAULT 0,
  `value` bigint(20) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_rank_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_size` int(11) NOT NULL DEFAULT 0,
  `day_rank_xp` int(11) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `growth_value` bigint(20) NOT NULL DEFAULT 0,
  `growth_score` bigint(20) NOT NULL DEFAULT 0,
  `growth_size` bigint(20) NOT NULL DEFAULT 0,
  `growth_xp` bigint(20) NOT NULL DEFAULT 0,
  `growth_rank_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_size` int(11) NOT NULL DEFAULT 0,
  `growth_rank_xp` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_xp` double(8,2) NOT NULL DEFAULT 0.00,
  `ratio` double(8,2) NOT NULL DEFAULT 0.00,
  `planet_count` int(11) NOT NULL DEFAULT 0,
  `day_planet_count` int(11) NOT NULL DEFAULT 0,
  `growth_planet_count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `galaxy_history`
--

DROP TABLE IF EXISTS `galaxy_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galaxy_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `galaxy_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint(20) NOT NULL,
  `score` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  `xp` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `galaxy_id` int(11) NOT NULL DEFAULT 0,
  `change_value` bigint(20) NOT NULL DEFAULT 0,
  `change_score` bigint(20) NOT NULL DEFAULT 0,
  `change_xp` bigint(20) NOT NULL DEFAULT 0,
  `change_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `planet_count` int(11) NOT NULL DEFAULT 0,
  `change_planet_count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inline_query`
--

DROP TABLE IF EXISTS `inline_query`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inline_query` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique identifier for this query',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `location` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `query` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `offset` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `inline_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `intel_change`
--

DROP TABLE IF EXISTS `intel_change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intel_change` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `planet_id` int(11) NOT NULL,
  `alliance_from_id` int(11) DEFAULT NULL,
  `alliance_to_id` int(11) DEFAULT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_seen` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jgp_scans`
--

DROP TABLE IF EXISTS `jgp_scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jgp_scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `chat_id` bigint(20) NOT NULL COMMENT 'Unique chat identifier',
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique message identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `date` datetime DEFAULT NULL COMMENT 'Date the message was sent in timestamp format',
  `forward_from` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier, sender of the original message',
  `forward_from_chat` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier, chat the original message belongs to',
  `forward_from_message_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier of the original message in the channel',
  `forward_date` datetime DEFAULT NULL COMMENT 'date the original message was sent in timestamp format',
  `reply_to_chat` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `reply_to_message` bigint(20) unsigned DEFAULT NULL COMMENT 'Message that this message is reply to',
  `media_group_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sticker` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voice` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `venue` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_chat_members` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `left_chat_member` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier, a member was removed from the group, information about them (this member may be the bot itself)',
  `new_chat_title` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_chat_photo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the chat photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created',
  `supergroup_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the supergroup has been created',
  `channel_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the channel chat has been created',
  `migrate_to_chat_id` bigint(20) DEFAULT NULL COMMENT 'Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier',
  `migrate_from_chat_id` bigint(20) DEFAULT NULL COMMENT 'Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier',
  `pinned_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connected_website` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forward_signature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `forward_sender_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `edit_date` bigint(20) NOT NULL,
  `author_signature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption_entities` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `successful_payment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `animation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `game` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `poll` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `passport_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply_markup` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`chat_id`,`id`),
  KEY `reply_to_chat_2` (`reply_to_chat`,`reply_to_message`),
  KEY `user_id` (`user_id`),
  KEY `forward_from` (`forward_from`),
  KEY `forward_from_chat` (`forward_from_chat`),
  KEY `reply_to_chat` (`reply_to_chat`),
  KEY `reply_to_message` (`reply_to_message`),
  KEY `left_chat_member` (`left_chat_member`),
  KEY `migrate_to_chat_id` (`migrate_to_chat_id`),
  KEY `migrate_from_chat_id` (`migrate_from_chat_id`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  CONSTRAINT `message_ibfk_3` FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  CONSTRAINT `message_ibfk_4` FOREIGN KEY (`forward_from_chat`) REFERENCES `chat` (`id`),
  CONSTRAINT `message_ibfk_5` FOREIGN KEY (`reply_to_chat`) REFERENCES `message` (`chat_id`),
  CONSTRAINT `message_ibfk_6` FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  CONSTRAINT `message_ibfk_7` FOREIGN KEY (`left_chat_member`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news_scans`
--

DROP TABLE IF EXISTS `news_scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planet_history`
--

DROP TABLE IF EXISTS `planet_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planet_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `planet_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `z` int(11) NOT NULL,
  `planet_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruler_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `score` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  `xp` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `change_value` bigint(20) NOT NULL DEFAULT 0,
  `change_score` bigint(20) NOT NULL DEFAULT 0,
  `change_xp` bigint(20) NOT NULL DEFAULT 0,
  `change_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planet_movements`
--

DROP TABLE IF EXISTS `planet_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planet_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `planet_id` int(11) NOT NULL,
  `from_x` int(11) DEFAULT NULL,
  `from_y` int(11) DEFAULT NULL,
  `from_z` int(11) DEFAULT NULL,
  `to_x` int(11) NOT NULL,
  `to_y` int(11) NOT NULL,
  `to_z` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planet_scans`
--

DROP TABLE IF EXISTS `planet_scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planet_scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roid_metal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roid_crystal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roid_eonium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `res_metal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `res_crystal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `res_eonium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `factory_usage_light` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `factory_usage_medium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `factory_usage_heavy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prod_res` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agents` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guards` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planets`
--

DROP TABLE IF EXISTS `planets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `planet_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alliance_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `galaxy_id` int(11) NOT NULL DEFAULT 0,
  `latest_p` int(11) DEFAULT NULL,
  `latest_d` int(11) DEFAULT NULL,
  `latest_u` int(11) DEFAULT NULL,
  `latest_au` int(11) DEFAULT NULL,
  `amps` int(11) NOT NULL DEFAULT 0,
  `dists` int(11) NOT NULL DEFAULT 0,
  `waves` int(11) NOT NULL DEFAULT 0,
  `min_alert` int(11) NOT NULL DEFAULT 0,
  `max_alert` int(11) NOT NULL DEFAULT 0,
  `total_cons` int(11) NOT NULL DEFAULT 0,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `z` int(11) NOT NULL,
  `planet_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruler_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `score` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  `xp` bigint(20) NOT NULL,
  `tick` int(11) NOT NULL,
  `covop_hit` tinyint(1) NOT NULL DEFAULT 0,
  `rank_size` bigint(20) NOT NULL DEFAULT 0,
  `rank_value` bigint(20) NOT NULL DEFAULT 0,
  `rank_score` bigint(20) NOT NULL DEFAULT 0,
  `rank_xp` bigint(20) NOT NULL DEFAULT 0,
  `stock_resources` bigint(20) NOT NULL DEFAULT 0,
  `prod_resources` bigint(20) NOT NULL DEFAULT 0,
  `government` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day_rank_value` int(11) NOT NULL DEFAULT 0,
  `day_rank_score` int(11) NOT NULL DEFAULT 0,
  `day_rank_size` int(11) NOT NULL DEFAULT 0,
  `day_rank_xp` int(11) NOT NULL DEFAULT 0,
  `day_value` bigint(20) NOT NULL DEFAULT 0,
  `day_score` bigint(20) NOT NULL DEFAULT 0,
  `day_size` bigint(20) NOT NULL DEFAULT 0,
  `day_xp` bigint(20) NOT NULL DEFAULT 0,
  `growth_value` int(11) NOT NULL DEFAULT 0,
  `growth_score` int(11) NOT NULL DEFAULT 0,
  `growth_size` int(11) NOT NULL DEFAULT 0,
  `growth_xp` int(11) NOT NULL DEFAULT 0,
  `growth_rank_value` int(11) NOT NULL DEFAULT 0,
  `growth_rank_score` int(11) NOT NULL DEFAULT 0,
  `growth_rank_size` int(11) NOT NULL DEFAULT 0,
  `growth_rank_xp` int(11) NOT NULL DEFAULT 0,
  `growth_percentage_value` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_score` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_size` double(8,2) NOT NULL DEFAULT 0.00,
  `growth_percentage_xp` double(8,2) NOT NULL DEFAULT 0.00,
  `round_roids` bigint(20) NOT NULL DEFAULT 0,
  `tick_roids` bigint(20) NOT NULL DEFAULT 0,
  `lost_roids` bigint(20) NOT NULL DEFAULT 0,
  `ticks_roided` int(11) NOT NULL DEFAULT 0,
  `ticks_roiding` int(11) NOT NULL DEFAULT 0,
  `rank_round_roids` int(11) NOT NULL DEFAULT 0,
  `rank_lost_roids` int(11) NOT NULL DEFAULT 0,
  `day_rank_round_roids` int(11) NOT NULL DEFAULT 0,
  `day_rank_lost_roids` int(11) NOT NULL DEFAULT 0,
  `latest_j` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_covopped` int(11) DEFAULT NULL,
  `latest_n` int(11) DEFAULT NULL,
  `age` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `planets_alliance_id_index` (`alliance_id`),
  KEY `planets_galaxy_id_index` (`galaxy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `politics`
--

DROP TABLE IF EXISTS `politics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `politics` (
  `allianceId` int(11) DEFAULT NULL,
  `status` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maxPlanets` int(11) DEFAULT NULL,
  `maxWaves` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `maxFleets` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `request_limiter`
--

DROP TABLE IF EXISTS `request_limiter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_limiter` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scan_queue`
--

DROP TABLE IF EXISTS `scan_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scan_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scan_queue_scan_id_unique` (`scan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=130312 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scan_requests`
--

DROP TABLE IF EXISTS `scan_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scan_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `scan_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=923 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scans`
--

DROP TABLE IF EXISTS `scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pa_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planet_id` int(11) NOT NULL,
  `tick` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `time` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `datetime` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `settings_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ships`
--

DROP TABLE IF EXISTS `ships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ships` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `init` int(11) NOT NULL,
  `guns` int(11) NOT NULL,
  `armor` int(11) NOT NULL,
  `damage` int(11) DEFAULT NULL,
  `empres` int(11) NOT NULL,
  `metal` int(11) NOT NULL,
  `crystal` int(11) NOT NULL,
  `eonium` int(11) NOT NULL,
  `total_cost` int(11) NOT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `eta` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `telegram_update`
--

DROP TABLE IF EXISTS `telegram_update`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telegram_update` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Update''s unique identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Unique message identifier',
  `inline_query_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Unique inline query identifier',
  `chosen_inline_result_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Local chosen inline result identifier',
  `callback_query_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Unique callback query identifier',
  `edited_message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Local edited message identifier',
  `channel_post_id` bigint(20) DEFAULT NULL,
  `edited_channel_post_id` bigint(20) DEFAULT NULL,
  `shipping_query_id` bigint(20) DEFAULT NULL,
  `pre_checkout_query_id` bigint(20) DEFAULT NULL,
  `poll_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `message_id` (`chat_id`,`message_id`),
  KEY `inline_query_id` (`inline_query_id`),
  KEY `chosen_inline_result_id` (`chosen_inline_result_id`),
  KEY `callback_query_id` (`callback_query_id`),
  KEY `edited_message_id` (`edited_message_id`),
  CONSTRAINT `telegram_update_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `message` (`chat_id`),
  CONSTRAINT `telegram_update_ibfk_2` FOREIGN KEY (`inline_query_id`) REFERENCES `inline_query` (`id`),
  CONSTRAINT `telegram_update_ibfk_3` FOREIGN KEY (`chosen_inline_result_id`) REFERENCES `chosen_inline_result` (`id`),
  CONSTRAINT `telegram_update_ibfk_4` FOREIGN KEY (`callback_query_id`) REFERENCES `callback_query` (`id`),
  CONSTRAINT `telegram_update_ibfk_5` FOREIGN KEY (`edited_message_id`) REFERENCES `edited_message` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ticks`
--

DROP TABLE IF EXISTS `ticks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tick` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `length` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planet_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `galaxy_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `alliance_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unit_scans`
--

DROP TABLE IF EXISTS `unit_scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit_scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) NOT NULL,
  `ship_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL COMMENT 'Unique user identifier',
  `is_bot` tinyint(1) DEFAULT 0 COMMENT 'True if this user is a bot',
  `first_name` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_code` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_chat`
--

DROP TABLE IF EXISTS `user_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_chat` (
  `user_id` bigint(20) NOT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) NOT NULL COMMENT 'Unique user or chat identifier',
  PRIMARY KEY (`user_id`,`chat_id`),
  KEY `chat_id` (`chat_id`),
  CONSTRAINT `user_chat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_chat_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `planet_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distorters` int(11) DEFAULT NULL,
  `military_centres` int(11) DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `tg_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stealth` int(11) NOT NULL DEFAULT 0,
  `allow_night` int(11) NOT NULL DEFAULT 1,
  `allow_notifications` int(11) NOT NULL DEFAULT 1,
  `notification_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_email_forward` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-23 10:24:12
