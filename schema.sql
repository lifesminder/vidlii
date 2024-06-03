-- MySQL dump 10.19  Distrib 10.3.31-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: vidlii_live
-- ------------------------------------------------------
-- Server version	10.3.31-MariaDB-0+deb10u1

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
-- Table structure for table `achievement_text`
--

DROP TABLE IF EXISTS `achievement_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `achievement_text` (
  `name` varchar(64) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `amount` int(10) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `achievement_users`
--

DROP TABLE IF EXISTS `achievement_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `achievement_users` (
  `username` varchar(20) NOT NULL,
  `name` varchar(64) NOT NULL,
  `type` varchar(12) NOT NULL COMMENT 's,v',
  `ach_date` date NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT 0,
  UNIQUE KEY `username` (`username`,`name`),
  KEY `username_2` (`username`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activations` (
  `username` varchar(20) NOT NULL,
  `secret` varchar(100) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `username` varchar(20) NOT NULL,
  `name` varchar(256) NOT NULL,
  `birthday` date NOT NULL,
  `country` varchar(5) NOT NULL,
  `what` varchar(500) NOT NULL,
  `why` varchar(500) NOT NULL,
  `date` datetime NOT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT 0,
  `review_time` int(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `badboys`
--

DROP TABLE IF EXISTS `badboys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `badboys` (
  `ip` varchar(64) NOT NULL,
  `submit_date` datetime NOT NULL DEFAULT current_timestamp(),
  `username` varchar(33) NOT NULL DEFAULT '',
  `agent` varchar(315) NOT NULL DEFAULT '',
  UNIQUE KEY `ip` (`ip`,`submit_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ban_reasons`
--

DROP TABLE IF EXISTS `ban_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ban_reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `content` varchar(50000) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulletins` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` varchar(500) NOT NULL,
  `by_user` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `by_user` (`by_user`)
) ENGINE=InnoDB AUTO_INCREMENT=20206 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `channel_banners`
--

DROP TABLE IF EXISTS `channel_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `channel_banners` (
  `username` varchar(20) NOT NULL,
  `links` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `channel_comments`
--

DROP TABLE IF EXISTS `channel_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `channel_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `on_channel` varchar(20) NOT NULL,
  `by_user` varchar(20) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `date` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `on_channel` (`on_channel`)
) ENGINE=InnoDB AUTO_INCREMENT=332753 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contest_entries`
--

DROP TABLE IF EXISTS `contest_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contest_entries` (
  `url` varchar(11) NOT NULL,
  `votes` int(10) NOT NULL,
  PRIMARY KEY (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contest_votes`
--

DROP TABLE IF EXISTS `contest_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contest_votes` (
  `url` varchar(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `converting`
--

DROP TABLE IF EXISTS `converting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `converting` (
  `url` varchar(11) NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `convert_status` tinyint(1) NOT NULL DEFAULT 0,
  `queue` int(255) NOT NULL,
  PRIMARY KEY (`url`),
  KEY `uploaded_on` (`uploaded_on`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feature_suggestions`
--

DROP TABLE IF EXISTS `feature_suggestions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feature_suggestions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `from_user` varchar(20) NOT NULL,
  `votes` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1628 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forgot_password`
--

DROP TABLE IF EXISTS `forgot_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forgot_password` (
  `username` varchar(20) NOT NULL,
  `code` varchar(50) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friends` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `friend_1` varchar(20) NOT NULL,
  `friend_2` varchar(20) NOT NULL,
  `by_user` varchar(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `sent_on` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `friend_1` (`friend_1`,`friend_2`),
  KEY `friend_1_2` (`friend_1`),
  KEY `friend_2` (`friend_2`),
  KEY `sent_on` (`sent_on`),
  KEY `friend_1_3` (`friend_1`,`friend_2`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=129677 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iprange_bans`
--

DROP TABLE IF EXISTS `iprange_bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iprange_bans` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `ip_range` varchar(255) NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mentions`
--

DROP TABLE IF EXISTS `mentions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mentions` (
  `video` int(6) DEFAULT NULL,
  `channel` varchar(20) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `date` datetime NOT NULL,
  `username` varchar(20) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  UNIQUE KEY `comment` (`video`,`username`),
  UNIQUE KEY `channel` (`channel`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `most_subscribed_month`
--

DROP TABLE IF EXISTS `most_subscribed_month`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `most_subscribed_month` (
  `username` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `most_subscribed_week`
--

DROP TABLE IF EXISTS `most_subscribed_week`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `most_subscribed_week` (
  `username` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `most_viewed_month`
--

DROP TABLE IF EXISTS `most_viewed_month`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `most_viewed_month` (
  `username` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `most_viewed_week`
--

DROP TABLE IF EXISTS `most_viewed_week`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `most_viewed_week` (
  `username` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options` (
  `option_name` varchar(50) NOT NULL,
  `value` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `playlists`
--

DROP TABLE IF EXISTS `playlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlists` (
  `purl` varchar(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `thumbnail` varchar(11) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`purl`),
  UNIQUE KEY `title` (`title`,`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `playlists_videos`
--

DROP TABLE IF EXISTS `playlists_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlists_videos` (
  `url` varchar(11) NOT NULL,
  `purl` varchar(11) NOT NULL,
  `position` int(10) NOT NULL,
  UNIQUE KEY `url` (`url`,`purl`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `private_messages`
--

DROP TABLE IF EXISTS `private_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `private_messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `from_user` varchar(20) NOT NULL,
  `to_user` varchar(20) NOT NULL,
  `message` varchar(10000) NOT NULL,
  `subject` varchar(256) NOT NULL DEFAULT '',
  `date_sent` datetime NOT NULL,
  `seen` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `from_user` (`from_user`),
  KEY `to_user` (`to_user`),
  KEY `seen` (`seen`)
) ENGINE=InnoDB AUTO_INCREMENT=90113 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recently_viewed`
--

DROP TABLE IF EXISTS `recently_viewed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recently_viewed` (
  `url` varchar(11) NOT NULL,
  `time_viewed` datetime NOT NULL,
  PRIMARY KEY (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `for_user` varchar(20) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `value` mediumtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `strikes`
--

DROP TABLE IF EXISTS `strikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `strikes` (
  `username` varchar(20) NOT NULL,
  `issued_by` varchar(20) NOT NULL,
  `issued_on` int(255) NOT NULL,
  `video_links` text NOT NULL,
  `comment` text NOT NULL,
  KEY `issued_by` (`issued_by`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `subscriber` varchar(20) NOT NULL,
  `subscription` varchar(20) NOT NULL,
  `submit_date` date NOT NULL DEFAULT '0000-00-00',
  `source` varchar(11) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `terminations`
--

DROP TABLE IF EXISTS `terminations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `terminations` (
  `username` varchar(20) NOT NULL,
  `secret` varchar(100) NOT NULL,
  `issued` int(10) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes` (
  `url` varchar(20) NOT NULL,
  `title` varchar(101) NOT NULL,
  `description` varchar(1100) NOT NULL,
  `category` int(1) NOT NULL,
  `logged_in` int(1) NOT NULL,
  `header` int(1) NOT NULL,
  `chrome` tinyint(1) NOT NULL,
  `firefox` tinyint(1) NOT NULL,
  `edge` tinyint(1) NOT NULL,
  `internet` tinyint(1) NOT NULL,
  `opera` tinyint(1) NOT NULL,
  `owner` varchar(20) NOT NULL,
  `upload_date` datetime NOT NULL,
  `installs` int(5) NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  PRIMARY KEY (`url`),
  FULLTEXT KEY `title` (`title`,`description`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploads` (
  `url` varchar(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0 = Upload, 1 = Change',
  `user` varchar(50) NOT NULL,
  `filesize` int(255) NOT NULL,
  `filetype` varchar(50) NOT NULL,
  `modified` varchar(50) NOT NULL,
  `token` varchar(11) NOT NULL,
  PRIMARY KEY (`url`),
  KEY `size` (`filesize`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(20) NOT NULL,
  `displayname` varchar(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(64) NOT NULL,
  `reg_date` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `strikes` tinyint(1) NOT NULL DEFAULT 0,
  `videos_watched` int(10) NOT NULL DEFAULT 0,
  `channel_views` int(10) NOT NULL DEFAULT 0,
  `channel_comments` int(10) NOT NULL DEFAULT 0,
  `video_views` int(10) NOT NULL DEFAULT 0,
  `videos` int(10) NOT NULL DEFAULT 0,
  `favorites` int(10) NOT NULL DEFAULT 0,
  `subscribers` int(10) NOT NULL DEFAULT 0,
  `subscriptions` int(10) NOT NULL DEFAULT 0,
  `friends` int(10) NOT NULL DEFAULT 0,
  `birthday` date NOT NULL,
  `country` varchar(5) NOT NULL DEFAULT '',
  `website` varchar(128) NOT NULL DEFAULT '',
  `about` varchar(500) NOT NULL DEFAULT '',
  `avatar` varchar(15) NOT NULL DEFAULT '',
  `bg_version` int(4) NOT NULL DEFAULT 1,
  `banner_version` int(4) NOT NULL DEFAULT 1,
  `channel_type` int(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `privacy` tinyint(1) NOT NULL DEFAULT 0 COMMENT '#0 = Public #1 = Unlisted #2 = Friends',
  `ban_reasons` varchar(50) NOT NULL DEFAULT '',
  `1st_latest_ip` varchar(128) NOT NULL,
  `2nd_latest_ip` varchar(128) NOT NULL DEFAULT '',
  `last_username_update` int(11) NOT NULL DEFAULT 0,
  `bg` varchar(6) NOT NULL DEFAULT 'ffffff',
  `chn_radius` int(1) NOT NULL DEFAULT 0,
  `avt_radius` int(1) DEFAULT 0,
  `nav` varchar(6) NOT NULL DEFAULT '89857F',
  `h_head` varchar(6) NOT NULL DEFAULT '666666',
  `h_head_fnt` varchar(6) NOT NULL DEFAULT 'ffffff',
  `h_in` varchar(6) NOT NULL DEFAULT 'eeeeee',
  `h_in_fnt` varchar(6) NOT NULL DEFAULT '6d6d6d',
  `n_head` varchar(6) NOT NULL DEFAULT '666666',
  `n_head_fnt` varchar(6) NOT NULL DEFAULT 'ffffff',
  `n_in` varchar(6) NOT NULL DEFAULT 'ffffff',
  `n_in_fnt` varchar(6) NOT NULL DEFAULT '000000',
  `links` varchar(6) NOT NULL DEFAULT '89857F',
  `font` int(1) NOT NULL DEFAULT 0,
  `b_avatar` varchar(7) NOT NULL DEFAULT '999999',
  `connect` varchar(32) NOT NULL DEFAULT '',
  `c_subscriber` tinyint(1) NOT NULL DEFAULT 1,
  `c_subscription` tinyint(1) NOT NULL DEFAULT 1,
  `c_friend` tinyint(1) NOT NULL DEFAULT 1,
  `c_featured` tinyint(1) NOT NULL DEFAULT 1,
  `featured_n_url` varchar(32) NOT NULL DEFAULT '',
  `featured_s_url` varchar(32) NOT NULL DEFAULT '',
  `snow` tinyint(1) NOT NULL DEFAULT 0,
  `mondo` tinyint(1) NOT NULL DEFAULT 0,
  `c_videos` tinyint(1) NOT NULL DEFAULT 1,
  `c_favorites` tinyint(1) NOT NULL DEFAULT 1,
  `c_comments` tinyint(1) NOT NULL DEFAULT 1,
  `c_featured_channels` tinyint(1) NOT NULL DEFAULT 0,
  `c_recent` tinyint(1) NOT NULL DEFAULT 1,
  `c_playlists` tinyint(1) NOT NULL DEFAULT 0,
  `playlists` varchar(256) NOT NULL DEFAULT '',
  `featured_channels` varchar(256) NOT NULL DEFAULT '',
  `bg_position` int(1) NOT NULL DEFAULT 0,
  `bg_repeat` int(1) DEFAULT 0,
  `bg_fixed` int(1) NOT NULL DEFAULT 0,
  `bg_stretch` int(1) NOT NULL DEFAULT 0,
  `h_trans` int(3) NOT NULL DEFAULT 0,
  `n_trans` int(3) DEFAULT 0,
  `channel_title` varchar(128) NOT NULL DEFAULT '',
  `channel_description` varchar(2500) NOT NULL DEFAULT '',
  `channel_tags` varchar(256) NOT NULL DEFAULT '',
  `i_name` varchar(128) DEFAULT '',
  `i_occupation` varchar(128) NOT NULL DEFAULT '',
  `i_schools` varchar(128) NOT NULL DEFAULT '',
  `i_interests` varchar(128) NOT NULL DEFAULT '',
  `i_movies` varchar(128) NOT NULL DEFAULT '',
  `i_music` varchar(128) NOT NULL DEFAULT '',
  `i_books` varchar(128) NOT NULL DEFAULT '',
  `a_name` tinyint(1) NOT NULL DEFAULT 1,
  `a_website` tinyint(1) NOT NULL DEFAULT 1,
  `a_description` tinyint(1) NOT NULL DEFAULT 1,
  `a_occupation` tinyint(1) NOT NULL DEFAULT 1,
  `a_schools` tinyint(1) NOT NULL DEFAULT 1,
  `a_interests` tinyint(1) NOT NULL DEFAULT 1,
  `a_movies` tinyint(1) NOT NULL DEFAULT 1,
  `a_music` tinyint(1) NOT NULL DEFAULT 1,
  `a_books` tinyint(1) DEFAULT 1,
  `a_last` tinyint(1) NOT NULL DEFAULT 1,
  `a_subs` tinyint(1) NOT NULL DEFAULT 1,
  `a_subs2` tinyint(1) NOT NULL DEFAULT 0,
  `a_country` tinyint(1) NOT NULL DEFAULT 1,
  `a_age` tinyint(1) NOT NULL DEFAULT 1,
  `ra_comments` tinyint(1) NOT NULL DEFAULT 1,
  `ra_favorites` tinyint(1) DEFAULT 1,
  `ra_friends` tinyint(1) NOT NULL DEFAULT 1,
  `activated` tinyint(1) NOT NULL DEFAULT 1,
  `channel_comment_privacy` tinyint(1) NOT NULL DEFAULT 0,
  `can_friend` tinyint(1) NOT NULL DEFAULT 1,
  `can_mention` tinyint(1) NOT NULL DEFAULT 1,
  `can_message` tinyint(1) NOT NULL DEFAULT 1,
  `featured_title` varchar(64) NOT NULL DEFAULT '',
  `theme` int(1) NOT NULL DEFAULT 0,
  `default_view` tinyint(1) NOT NULL DEFAULT 0,
  `c_all` tinyint(1) NOT NULL DEFAULT 1,
  `channel_version` int(1) NOT NULL DEFAULT 1,
  `subscriber_d` tinyint(1) NOT NULL DEFAULT 0,
  `subscription_d` tinyint(1) NOT NULL DEFAULT 0,
  `friends_d` tinyint(1) NOT NULL DEFAULT 0,
  `featured_d` tinyint(1) NOT NULL DEFAULT 1,
  `channel_d` tinyint(1) NOT NULL DEFAULT 1,
  `recent_d` tinyint(1) NOT NULL DEFAULT 1,
  `c_custom` tinyint(1) NOT NULL DEFAULT 0,
  `custom_d` tinyint(1) NOT NULL DEFAULT 1,
  `modules_vertical_r` varchar(24) NOT NULL DEFAULT 'cu,re,ft,s2,s1,fr,co',
  `modules_vertical_l` varchar(24) NOT NULL DEFAULT 'cu,re,ft,s2,s1,fr,co',
  `custom` varchar(1024) NOT NULL DEFAULT '',
  `partner` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_mod` tinyint(1) NOT NULL DEFAULT 0,
  `shadowbanned` tinyint(1) NOT NULL DEFAULT 0,
  `adsense` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`username`),
  KEY `displayname` (`displayname`),
  KEY `shadowbanned` (`shadowbanned`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_block`
--

DROP TABLE IF EXISTS `users_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_block` (
  `blocker` varchar(20) NOT NULL,
  `blocked` varchar(20) NOT NULL,
  KEY `blocker` (`blocker`),
  KEY `blocked` (`blocked`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_oldnames`
--

DROP TABLE IF EXISTS `users_oldnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_oldnames` (
  `displayname` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_remembers`
--

DROP TABLE IF EXISTS `users_remembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_remembers` (
  `uid` varchar(20) NOT NULL,
  `code` varchar(32) NOT NULL,
  `browser` varchar(16) NOT NULL,
  `last_login` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video_comments`
--

DROP TABLE IF EXISTS `video_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(11) NOT NULL,
  `comment` mediumtext NOT NULL,
  `by_user` varchar(20) NOT NULL,
  `date_sent` datetime NOT NULL,
  `rating` int(10) NOT NULL DEFAULT 0,
  `has_replies` tinyint(1) NOT NULL DEFAULT 0,
  `reply_to` int(10) NOT NULL DEFAULT 0,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `raters` varchar(10000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `reply_to` (`reply_to`),
  KEY `seen` (`seen`),
  KEY `date_sent` (`date_sent`),
  KEY `by_user` (`by_user`),
  KEY `rating` (`rating`)
) ENGINE=InnoDB AUTO_INCREMENT=350949 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video_favorites`
--

DROP TABLE IF EXISTS `video_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_favorites` (
  `url` varchar(11) NOT NULL,
  `favorite_by` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  UNIQUE KEY `url` (`url`,`favorite_by`),
  KEY `date` (`date`),
  KEY `favorite_by` (`favorite_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video_ratings`
--

DROP TABLE IF EXISTS `video_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_ratings` (
  `url` varchar(11) NOT NULL,
  `user_rated` varchar(20) NOT NULL,
  `stars` tinyint(1) NOT NULL,
  `submit_date` datetime NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `url` (`url`,`user_rated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video_responses`
--

DROP TABLE IF EXISTS `video_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(11) NOT NULL,
  `url_response` varchar(11) NOT NULL,
  `date` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `accepted` tinyint(1) NOT NULL DEFAULT 0,
  `response_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`,`accepted`)
) ENGINE=InnoDB AUTO_INCREMENT=6269 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `url` varchar(11) NOT NULL,
  `file` varchar(80) NOT NULL,
  `hd` tinyint(1) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL DEFAULT '',
  `tags` varchar(250) NOT NULL DEFAULT '',
  `category` int(2) NOT NULL DEFAULT 1,
  `privacy` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Pub, 1 = Pri, 2 = Unli',
  `uploaded_by` varchar(20) NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `views` int(10) NOT NULL DEFAULT 0,
  `displayviews` int(7) NOT NULL DEFAULT 0,
  `watched` mediumint(7) NOT NULL DEFAULT 0,
  `comments` int(10) NOT NULL DEFAULT 0,
  `responses` int(5) NOT NULL DEFAULT 0,
  `favorites` int(7) NOT NULL DEFAULT 0,
  `1_star` int(10) NOT NULL DEFAULT 0,
  `2_star` int(10) NOT NULL DEFAULT 0,
  `3_star` int(10) NOT NULL DEFAULT 0,
  `4_star` int(10) NOT NULL DEFAULT 0,
  `5_star` int(10) NOT NULL DEFAULT 0,
  `length` int(10) NOT NULL DEFAULT 0,
  `s_comments` tinyint(1) NOT NULL DEFAULT 1,
  `s_ratings` tinyint(1) NOT NULL DEFAULT 1,
  `s_responses` tinyint(1) NOT NULL DEFAULT 1,
  `s_related` tinyint(1) NOT NULL DEFAULT 1,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `frontpage` tinyint(1) NOT NULL DEFAULT 0,
  `most_popular` tinyint(1) NOT NULL DEFAULT 1,
  `banned_uploader` tinyint(1) NOT NULL DEFAULT 0,
  `shadowbanned_uploader` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `thumbs` tinyint(1) NOT NULL DEFAULT -1,
  `show_ads` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`url`),
  KEY `uploaded_by` (`uploaded_by`),
  KEY `featured` (`featured`),
  KEY `uploaded_on` (`uploaded_on`),
  KEY `privacy` (`privacy`,`banned_uploader`,`shadowbanned_uploader`,`status`),
  FULLTEXT KEY `FullText` (`title`,`description`,`tags`),
  FULLTEXT KEY `FullText2` (`title`,`description`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos_deleted`
--

DROP TABLE IF EXISTS `videos_deleted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos_deleted` (
  `id` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos_flags`
--

DROP TABLE IF EXISTS `videos_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos_flags` (
  `url` varchar(11) NOT NULL,
  `by_user` varchar(20) NOT NULL,
  `reason` int(2) NOT NULL,
  `submit_on` datetime NOT NULL,
  UNIQUE KEY `url` (`url`,`by_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos_schedule`
--

DROP TABLE IF EXISTS `videos_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos_schedule` (
  `id` varchar(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos_views`
--

DROP TABLE IF EXISTS `videos_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos_views` (
  `vid` varchar(11) NOT NULL,
  `views` smallint(4) NOT NULL,
  `submit_date` date NOT NULL DEFAULT '0000-00-00',
  `source` varchar(64) NOT NULL,
  UNIQUE KEY `vid` (`vid`,`submit_date`,`source`),
  KEY `views` (`views`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `videos_watched`
--

DROP TABLE IF EXISTS `videos_watched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos_watched` (
  `vid` varchar(11) NOT NULL,
  `watchtime` mediumint(7) NOT NULL,
  `submit_date` date NOT NULL,
  UNIQUE KEY `vid` (`vid`,`submit_date`),
  KEY `watchtime` (`watchtime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wrong_logins`
--

DROP TABLE IF EXISTS `wrong_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wrong_logins` (
  `ip` varchar(128) NOT NULL,
  `submit_date` datetime NOT NULL,
  `channel` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`name`, `value`) VALUES
('channels', '1'),
('guidelines', '<div class=\"vc_r\" style=\"margin-bottom:0\">\r\n  <h2 style=\"border-bottom: 1px solid #ccc; margin: 0 0 10px;\">About the LiiVid Guidelines</h2>\r\n  <p>The LiiVid Guidelines are meant to be easily comprehensible and straightforward. We strive to make them inclusive and accommodating to everyone. We request you to abide by our concise set of rules to ensure a safe and positive experience for everyone.</p>\r\n  <h2 style=\"border-bottom: 1px solid #ccc;\">The Rules</h2>\r\n  <ul class=\"cgul\" style=\"padding-left: 29px;\">\r\n    <li><b>Respect Others:</b> LiiVid is a community of individuals with diverse opinions and backgrounds. We expect everyone to treat each other with respect and kindness. Any content that promotes hate speech, harassment, discrimination, or violence will not be tolerated on our platform.</li>\r\n    <li><b>Keep it Legal:</b> You must follow all applicable laws and regulations when using LiiVid. Do not upload or share any content that violates copyright, trademark, or any other intellectual property laws. Also, do not share content that is illegal or violates any other laws.</li>\r\n    <li><b>No Nudity or Sexual Content:</b> LiiVid is not a platform for pornography or sexually explicit content. Any content that contains nudity or sexual content will be removed from our platform.</li>\r\n    <li><b>No Spam:</b> We do not allow spam on our platform. Do not upload or share content that is intended to promote or sell illicit goods, products or services.</li>\r\n    <li><b>Age Restriction:</b> Users must be at least 13 years old to use LiiVid. If you are under 18 years old, you must have the consent of a parent or legal guardian to use our platform.</li>\r\n    <li><b>Reporting:</b> If you come across any content that violates our guidelines or community standards, please report it immediately. We take all reports seriously and will take appropriate action.</li>\r\n    <li><b>Be Authentic:</b> We encourage you to be yourself on LiiVid. Do not upload or share any content that is fake or misleading. You are responsible for the content you upload or share on our platform.</li>\r\n  </ul>\r\n<h2 style=\"border-bottom: 1px solid #ccc; margin: 22px 0 10px;\">We Enforce These Guidelines</h2>\r\n<p>Periodically, our staff at LiiVid reviews flagged videos to ensure they adhere to our Community Guidelines. If we find any violation, we take necessary action to remove them. Any account found violating these guidelines will be penalized, and repeated or severe violations may lead to account termination. We urge you to follow these guidelines without trying to find any loopholes or attempting to circumvent them by any means.</p></div>'),
('help', '<strong>Q: Is LiiVid a free service?</strong>\r\n\r\n<p>A: Yes, LiiVid is completely free to use with no strings attached.</p>\r\n<strong>Q: How do I become a moderator on LiiVid?\r\n</strong>A: You don\'t :)\r\n\r\n<strong>Q: How can I share a video on LiiVid?</strong>\r\n\r\n<p>A: To share a video on LiiVid, simply copy the embed code provided under the video description.</p>\r\n<strong>Q: What is the maximum video length allowed on LiiVid?</strong>\r\n\r\n<p>A: Regular users can upload videos up to 25 minutes long, while partners can upload videos up to 35 minutes long.</p>\r\n<strong>Q: What file formats are accepted for video uploads on LiiVid?</strong>\r\n\r\n<p>A: LiiVid accepts video uploads in the following formats: .3GP, .AVI, .FLV, .MOV, .MP4, .MPEG, .MPG, .WEBM, and .WMV.</p>\r\n<strong>Q: What is LiiVid\'s policy on copyright infringement?</strong>\r\n\r\n<p>A: LiiVid respects the rights of copyright holders and publishers and only accepts video uploads from persons who hold all necessary rights to the uploaded material. LiiVid\'s policy is to respond to any notices of alleged infringement that comply with the Digital Millennium Copyright Act (DMCA).</p>\r\n<strong>Q: Why do I keep getting asked to log in on LiiVid?</strong>\r\n\r\n<p>A: If LiiVid keeps asking you to log in, make sure you have cookies enabled on your browser.</p>\r\n<strong>Q: What are tags used for on LiiVid?</strong>\r\n\r\n<p>A: Tags are basically keywords that describe your videos, making them easier to find by other users.</p>\r\n<strong>Q: How do I delete one of my videos on LiiVid?</strong>\r\n\r\n<p>A: To delete a video on LiiVid, simply go to the watch page of your video and click on the \"Delete Video\" button right under the video description. Alternatively, you can also go to the \"My Videos\" page and delete a video from there.</p>\r\n<strong>Q: What is the size limit for video uploads on LiiVid?</strong>\r\n\r\n<p>A: The size limit for video uploads on LiiVid is 2 GB per video, and each user is limited to a maximum of 8 video uploads per day.</p>\r\n<strong>Q: How can I report a video on LiiVid?</strong>\r\n\r\n<p>A: To report a video on LiiVid, click the \"Flag\" button below the video player and report the video for the rule that it\'s breaking.</p>'),
('login', '1'),
('logo', '3F9B'),
('settings', '0'),
('signup', '1'),
('top_text', ''),
('uploader', '1'),
('videos', '1');
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-26  1:30:12
