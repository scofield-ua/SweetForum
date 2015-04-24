-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 23, 2015 at 05:57 PM
-- Server version: 5.1.65-community-log
-- PHP Version: 5.3.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) unsigned NOT NULL,
  `aco_id` int(10) unsigned NOT NULL,
  `_create` char(2) NOT NULL DEFAULT '0',
  `_read` char(2) NOT NULL DEFAULT '0',
  `_update` char(2) NOT NULL DEFAULT '0',
  `_delete` char(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `text` text NOT NULL,
  `url` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `answer_to` int(11) DEFAULT NULL COMMENT 'ID of comment answer',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1 means delete; 0 means nothing',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `answer_to` (`answer_to`),
  KEY `user_id_2` (`user_id`),
  KEY `topic_id_2` (`topic_id`),
  KEY `answer_to_2` (`answer_to`),
  KEY `answer_to_3` (`answer_to`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments_activity`
--

CREATE TABLE IF NOT EXISTS `comments_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flag` tinyint(4) NOT NULL COMMENT 'Type of mark',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`),
  KEY `user_id` (`user_id`),
  KEY `comment_id_2` (`comment_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments_info`
--

CREATE TABLE IF NOT EXISTS `comments_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  `is_modified` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True means that comments was modified',
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `comment_likes_notifications`
--
CREATE TABLE IF NOT EXISTS `comment_likes_notifications` (
`topic_id` int(11)
,`comment_id` int(11)
,`comment_hash_id` varchar(100)
,`from_user_id` int(11)
,`to_user_id` int(11)
,`from_user_name` varchar(200)
,`from_user_username` varchar(100)
,`from_user_avatar` varchar(500)
,`created` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_id` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL COMMENT 'ID entity which raised Like',
  `type` tinyint(4) NOT NULL DEFAULT '-1' COMMENT '0 - topic, 1 - comment',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hash_id` varchar(255) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - nothing, -1 - deleted',
  `checked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - message checked by user',
  `is_modified` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `to_user_id` (`to_user_id`,`from_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages_info`
--

CREATE TABLE IF NOT EXISTS `messages_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages_users_archived`
--

CREATE TABLE IF NOT EXISTS `messages_users_archived` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `by_user_id` int(11) NOT NULL COMMENT 'Archived by user id',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `by_user_id` (`by_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages_users_blocked`
--

CREATE TABLE IF NOT EXISTS `messages_users_blocked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `by_user_id` int(11) NOT NULL COMMENT 'Blocked by user id',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`by_user_id`),
  KEY `by_user_id` (`by_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(10) NOT NULL DEFAULT 'eng',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE IF NOT EXISTS `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `text` mediumtext NOT NULL,
  `url` varchar(250) NOT NULL,
  `is_modified` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `thread_id` (`thread_id`),
  KEY `user_id` (`user_id`),
  KEY `thread_id_2` (`thread_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `topics_activity`
--

CREATE TABLE IF NOT EXISTS `topics_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flag` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`,`user_id`),
  KEY `topic_id_2` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `topic_likes_notifications`
--
CREATE TABLE IF NOT EXISTS `topic_likes_notifications` (
`topic_id` int(11)
,`topic_name` varchar(250)
,`topic_url` varchar(250)
,`from_user_id` int(11)
,`to_user_id` int(11)
,`from_user_name` varchar(200)
,`from_user_username` varchar(100)
,`from_user_avatar` varchar(500)
,`created` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_id` varchar(250) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1 means ban',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hash_id` (`hash_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `avatar` varchar(500) NOT NULL COMMENT 'URL for avatar',
  `social` varchar(250) DEFAULT NULL COMMENT 'Social network URL',
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_notification`
--

CREATE TABLE IF NOT EXISTS `users_notification` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `new_topic_comment` tinyint(1) NOT NULL DEFAULT '1',
  `new_private_message` tinyint(1) NOT NULL DEFAULT '1',
  `modified` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_privacy`
--

CREATE TABLE IF NOT EXISTS `users_privacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `show_social_link` tinyint(1) NOT NULL DEFAULT '1',
  `stop_private_messages` tinyint(1) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_reports`
--

CREATE TABLE IF NOT EXISTS `users_reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Who to mark',
  `by_user_id` int(11) NOT NULL COMMENT 'Who sent report',
  `report_message` text,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`by_user_id`),
  KEY `by_user_id` (`by_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_resend_password`
--

CREATE TABLE IF NOT EXISTS `users_resend_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hash_id` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Structure for view `comment_likes_notifications`
--
DROP TABLE IF EXISTS `comment_likes_notifications`;

CREATE VIEW `comment_likes_notifications` AS select `comments`.`topic_id` AS `topic_id`,`comments`.`id` AS `comment_id`,`comments`.`hash_id` AS `comment_hash_id`,`likes`.`user_id` AS `from_user_id`,`comments`.`user_id` AS `to_user_id`,`users_info`.`name` AS `from_user_name`,`users_info`.`username` AS `from_user_username`,`users_info`.`avatar` AS `from_user_avatar`,`likes`.`created` AS `created` from ((`likes` join `users_info` on((`likes`.`user_id` = `users_info`.`user_id`))) join `comments` on((`likes`.`type_id` = `comments`.`id`))) where ((`likes`.`type` = 1) and (`likes`.`user_id` <> `comments`.`user_id`));

-- --------------------------------------------------------

--
-- Structure for view `topic_likes_notifications`
--
DROP TABLE IF EXISTS `topic_likes_notifications`;

CREATE VIEW `topic_likes_notifications` AS select `topics`.`id` AS `topic_id`,`topics`.`name` AS `topic_name`,`topics`.`url` AS `topic_url`,`likes`.`user_id` AS `from_user_id`,`topics`.`user_id` AS `to_user_id`,`users_info`.`name` AS `from_user_name`,`users_info`.`username` AS `from_user_username`,`users_info`.`avatar` AS `from_user_avatar`,`likes`.`created` AS `created` from ((`likes` join `users_info` on((`likes`.`user_id` = `users_info`.`user_id`))) join `topics` on((`likes`.`type_id` = `topics`.`id`))) where ((`likes`.`type` = 0) and (`likes`.`user_id` <> `topics`.`user_id`));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`answer_to`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments_activity`
--
ALTER TABLE `comments_activity`
  ADD CONSTRAINT `comments_activity_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_activity_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments_info`
--
ALTER TABLE `comments_info`
  ADD CONSTRAINT `comments_info_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages_info`
--
ALTER TABLE `messages_info`
  ADD CONSTRAINT `messages_info_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages_users_archived`
--
ALTER TABLE `messages_users_archived`
  ADD CONSTRAINT `messages_users_archived_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_users_archived_ibfk_2` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages_users_blocked`
--
ALTER TABLE `messages_users_blocked`
  ADD CONSTRAINT `messages_users_blocked_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_users_blocked_ibfk_2` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topics_activity`
--
ALTER TABLE `topics_activity`
  ADD CONSTRAINT `topics_activity_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topics_activity_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_info`
--
ALTER TABLE `users_info`
  ADD CONSTRAINT `users_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_notification`
--
ALTER TABLE `users_notification`
  ADD CONSTRAINT `users_notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `users_privacy`
--
ALTER TABLE `users_privacy`
  ADD CONSTRAINT `users_privacy_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_reports`
--
ALTER TABLE `users_reports`
  ADD CONSTRAINT `users_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_reports_ibfk_2` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_resend_password`
--
ALTER TABLE `users_resend_password`
  ADD CONSTRAINT `users_resend_password_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
