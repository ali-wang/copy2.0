/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : thinkgrab

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-06-03 11:22:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wxgroup`
-- ----------------------------
DROP TABLE IF EXISTS `wxgroup`;
CREATE TABLE `wxgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户id',
  `groupname` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '组名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wxgroup
-- ----------------------------
INSERT INTO `wxgroup` VALUES ('16', '1', '测试组2');
INSERT INTO `wxgroup` VALUES ('20', '1', '13213');
INSERT INTO `wxgroup` VALUES ('22', '1', '风阻23');
