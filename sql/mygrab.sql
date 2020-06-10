/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : mygrab

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-06-10 16:30:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `allshow`
-- ----------------------------
DROP TABLE IF EXISTS `allshow`;
CREATE TABLE `allshow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) DEFAULT NULL,
  `souword` varchar(255) DEFAULT NULL,
  `copy_content` varchar(255) DEFAULT NULL,
  `sourceType` varchar(255) DEFAULT NULL,
  `equipment` varchar(255) DEFAULT NULL COMMENT '设备',
  `user_type` tinyint(4) DEFAULT NULL,
  `user_ip` varchar(100) DEFAULT NULL,
  `utm_medium` varchar(255) DEFAULT NULL,
  `utm_content` varchar(255) DEFAULT NULL,
  `utm_term` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `sign_id` varchar(255) DEFAULT NULL,
  `stop` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=265401 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of allshow
-- ----------------------------
INSERT INTO `allshow` VALUES ('265397', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '', '1231', '--', 'iphone', '1', '127.0.0.1', '', '', '', '--', '--', '1591612940', '21', '19');
INSERT INTO `allshow` VALUES ('265396', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '', '123456789', '--', 'iphone', '1', '127.0.0.1', '', '', '', '--', '--', '1591589288', '21', '133');
INSERT INTO `allshow` VALUES ('265398', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '', '123456789', '--', 'iphone', '1', '127.0.0.1', '', '', '', '--', '--', '1591666037', '21', '8');
INSERT INTO `allshow` VALUES ('265399', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '', '123456789', '--', 'iphone', '1', '127.0.0.1', '', '', '', '--', '--', '1591667456', '21', '1427');
INSERT INTO `allshow` VALUES ('265400', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '', '123456789', '--', 'iphone', '1', '127.0.0.1', '', '', '', '--', '--', '1591763184', '21', '11');

-- ----------------------------
-- Table structure for `wxshow`
-- ----------------------------
DROP TABLE IF EXISTS `wxshow`;
CREATE TABLE `wxshow` (
  `wid` int(11) NOT NULL AUTO_INCREMENT,
  `wsouword` varchar(255) DEFAULT NULL,
  `wsign_id` varchar(255) DEFAULT NULL,
  `wlocation` varchar(255) DEFAULT NULL,
  `wtime` varchar(255) DEFAULT NULL,
  `wutm_medium` varchar(255) DEFAULT NULL,
  `wutm_content` varchar(255) DEFAULT NULL,
  `wutm_term` varchar(255) DEFAULT NULL,
  `wuser_ip` varchar(20) NOT NULL COMMENT '用户ip',
  `wform` varchar(255) DEFAULT NULL,
  `wregion` varchar(255) DEFAULT NULL,
  `wcity` varchar(255) DEFAULT NULL,
  `wequipment` varchar(255) DEFAULT NULL,
  `wsourcetype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM AUTO_INCREMENT=7270345 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxshow
-- ----------------------------
INSERT INTO `wxshow` VALUES ('7270328', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612921', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'iphone', '--');
INSERT INTO `wxshow` VALUES ('7270327', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612915', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'iphone', '--');
INSERT INTO `wxshow` VALUES ('7270329', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612924', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270330', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612927', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270331', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612928', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270332', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612928', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270333', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612928', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270334', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612929', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270335', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612929', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270336', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612929', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270337', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612929', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270338', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612929', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270339', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612929', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270340', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591612929', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270341', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591666029', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
INSERT INTO `wxshow` VALUES ('7270342', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591666033', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'iphone', '--');
INSERT INTO `wxshow` VALUES ('7270343', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591667452', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'iphone', '--');
INSERT INTO `wxshow` VALUES ('7270344', '', '21', 'http://127.0.0.1/%E6%B5%8B%E8%AF%95wxdy/index.html', '1591763173', '', '', '', '127.0.0.1', 'undefined', '--', '--', 'PC', '--');
