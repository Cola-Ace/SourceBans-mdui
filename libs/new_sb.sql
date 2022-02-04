/*
 Navicat Premium Data Transfer

 Source Server         : muyea
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : muyeaqwq.mysql.rds.aliyuncs.com:3306
 Source Schema         : new_sb

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 20/01/2022 11:16:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sb_bans
-- ----------------------------
DROP TABLE IF EXISTS `sb_bans`;
CREATE TABLE `sb_bans`  (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `steamid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '32',
  `steam64` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '64',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ban_time` int(11) NOT NULL COMMENT 'timestamp',
  `end_time` int(11) NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `length` int(11) NOT NULL COMMENT 'second',
  `remove_time` int(1) NOT NULL,
  `remove_user` int(10) NOT NULL COMMENT 'uid',
  PRIMARY KEY (`bid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sb_commons
-- ----------------------------
DROP TABLE IF EXISTS `sb_commons`;
CREATE TABLE `sb_commons`  (
  `cid` int(10) NOT NULL AUTO_INCREMENT,
  `steamid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '32',
  `steam64` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '64',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ban_time` int(11) NOT NULL COMMENT 'timestamp',
  `end_time` int(11) NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `length` int(11) NOT NULL COMMENT 'second',
  `remove_time` int(1) NOT NULL,
  `remove_user` int(10) NOT NULL COMMENT 'uid',
  PRIMARY KEY (`cid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sb_servers
-- ----------------------------
DROP TABLE IF EXISTS `sb_servers`;
CREATE TABLE `sb_servers`  (
  `sid` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `port` int(5) NOT NULL,
  `rcon_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  PRIMARY KEY (`sid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sb_users
-- ----------------------------
DROP TABLE IF EXISTS `sb_users`;
CREATE TABLE `sb_users`  (
  `uid` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `reg_timestamp` int(11) NOT NULL COMMENT '注册时间',
  `last_login` int(11) NOT NULL COMMENT '上次登录时间',
  `permission` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
