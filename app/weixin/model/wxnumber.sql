/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : thinkgrab

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-06-03 11:24:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wxnumber`
-- ----------------------------
DROP TABLE IF EXISTS `wxnumber`;
CREATE TABLE `wxnumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '微信号自增id',
  `uid` int(11) NOT NULL COMMENT '添加管理员id',
  `gid` int(11) NOT NULL COMMENT '添加组id',
  `number` char(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '123456' COMMENT '微信号',
  `name` char(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '微信名称',
  `imgurl` char(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '二维码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wxnumber
-- ----------------------------
INSERT INTO `wxnumber` VALUES ('85', '1', '20', '121231132', '测试1', '');
INSERT INTO `wxnumber` VALUES ('89', '1', '22', '12313231321313', '测试redis1', '');
INSERT INTO `wxnumber` VALUES ('79', '1', '16', '123131313213', '策划书redis', '');
INSERT INTO `wxnumber` VALUES ('84', '1', '20', '131332', '测试2', '');
INSERT INTO `wxnumber` VALUES ('78', '1', '16', '123131313213', '测试redis', '');
INSERT INTO `wxnumber` VALUES ('90', '1', '22', '123132465454', '测试redis2', '');
