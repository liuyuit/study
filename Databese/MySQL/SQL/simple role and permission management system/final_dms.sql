/*
Navicat MySQL Data Transfer

Source Server         : my-own-tencent-mysql-182.254.227.214
Source Server Version : 50732
Source Host           : 182.254.227.214:3306
Source Database       : dms

Target Server Type    : MYSQL
Target Server Version : 50732
File Encoding         : 65001

Date: 2021-02-03 11:10:38
*/

SET FOREIGN_KEY_CHECKS=0;
create database dms;
use dms;

-- ----------------------------
-- Table structure for t_apply
-- ----------------------------
DROP TABLE IF EXISTS `t_apply`;
CREATE TABLE `t_apply` (
  `t_apply_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_apply_applicant_id` int(11) DEFAULT NULL,
  `t_group_num` varchar(22) DEFAULT NULL,
  `t_apply_time` datetime DEFAULT NULL,
  `t_apply_state` int(2) DEFAULT NULL COMMENT '0申请未通过 1申请已通过 2待审核',
  `t_use_start_time` datetime DEFAULT NULL,
  `t_use_end_time` datetime DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`t_apply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_apply
-- ----------------------------

-- ----------------------------
-- Table structure for t_borrow
-- ----------------------------
DROP TABLE IF EXISTS `t_borrow`;
CREATE TABLE `t_borrow` (
  `t_borrow_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_borrow_device_rfid_num` int(11) DEFAULT NULL,
  `t_borrow_start_time` datetime DEFAULT NULL,
  `t_borrow_end_time` datetime DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`t_borrow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_borrow
-- ----------------------------

-- ----------------------------
-- Table structure for t_circle
-- ----------------------------
DROP TABLE IF EXISTS `t_circle`;
CREATE TABLE `t_circle` (
  `t_circle_id` int(11) NOT NULL,
  `t_borrow_id` int(11) DEFAULT NULL,
  `t_circle_state` int(11) DEFAULT NULL,
  `t_borrow_user_num` int(11) DEFAULT NULL,
  `t_circle_start_time` datetime DEFAULT NULL,
  `t_circle_end_time` datetime DEFAULT NULL,
  `t_circle_expect_time` datetime DEFAULT NULL,
  `t_circle_location` int(11) DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`t_circle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_circle
-- ----------------------------

-- ----------------------------
-- Table structure for t_dept
-- ----------------------------
DROP TABLE IF EXISTS `t_dept`;
CREATE TABLE `t_dept` (
  `t_dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_dept_name` varchar(22) DEFAULT NULL,
  `t_manager_id` int(11) DEFAULT NULL,
  `spare1` varchar(200) DEFAULT NULL,
  `spare2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`t_dept_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_dept
-- ----------------------------
INSERT INTO `t_dept` VALUES ('1', '技术部', '1', 'spare message', 'spare2 message');

-- ----------------------------
-- Table structure for t_device
-- ----------------------------
DROP TABLE IF EXISTS `t_device`;
CREATE TABLE `t_device` (
  `t_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_device_rfid_num` varchar(22) NOT NULL COMMENT 'RFID电子编号',
  `t_device_name` varchar(22) NOT NULL COMMENT '设备名称',
  `t_device_type` int(2) NOT NULL COMMENT '设备类型',
  `t_device_level` int(2) NOT NULL COMMENT '0 普通设备 1重要设备',
  `t_device_department` int(11) NOT NULL,
  `t_device_state` int(2) NOT NULL DEFAULT '1' COMMENT '0空闲1借出2已预定',
  `t_device_location` varchar(22) DEFAULT NULL COMMENT '设备位置',
  `t_reg_time` datetime DEFAULT NULL,
  `t_device_del` int(2) DEFAULT '1',
  `t_device_range` int(2) DEFAULT NULL COMMENT '0公司内 1公司内外均可',
  `t_device_price` double(100,0) DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`t_device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_device
-- ----------------------------

-- ----------------------------
-- Table structure for t_device_faultinfo
-- ----------------------------
DROP TABLE IF EXISTS `t_device_faultinfo`;
CREATE TABLE `t_device_faultinfo` (
  `t_device_id` int(11) NOT NULL,
  `t_device_fault_type` varchar(200) DEFAULT '' COMMENT '故障类型',
  `t_device_fault_detail` varchar(200) DEFAULT NULL,
  `t_fault_time` datetime DEFAULT NULL,
  `t_fault_state` int(2) DEFAULT NULL COMMENT '0未处理 1已处理',
  `spare1` varchar(200) DEFAULT NULL,
  `spare2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`t_device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_device_faultinfo
-- ----------------------------

-- ----------------------------
-- Table structure for t_device_power
-- ----------------------------
DROP TABLE IF EXISTS `t_device_power`;
CREATE TABLE `t_device_power` (
  `t_device_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_group_id` int(11) NOT NULL,
  `t_power_start_time` datetime DEFAULT NULL,
  `t_power_end_time` datetime DEFAULT NULL,
  PRIMARY KEY (`t_device_id`,`t_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_device_power
-- ----------------------------

-- ----------------------------
-- Table structure for t_device_type
-- ----------------------------
DROP TABLE IF EXISTS `t_device_type`;
CREATE TABLE `t_device_type` (
  `device_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_type_name` varchar(22) NOT NULL,
  `desc` varchar(100) DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`device_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_device_type
-- ----------------------------

-- ----------------------------
-- Table structure for t_group
-- ----------------------------
DROP TABLE IF EXISTS `t_group`;
CREATE TABLE `t_group` (
  `t_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_group_num` varchar(22) NOT NULL,
  `t_group_name` varchar(22) DEFAULT NULL,
  `t_reg_time` datetime DEFAULT NULL,
  `t_group_leader` int(11) DEFAULT NULL COMMENT '团队负责人',
  `t_group_state` int(2) DEFAULT NULL COMMENT '0无效 1有效 2待审核',
  `t_group_del` int(2) DEFAULT '1' COMMENT '0无效信息 1有效信息',
  `t_group_desc` varchar(255) DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`t_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_group
-- ----------------------------

-- ----------------------------
-- Table structure for t_group_member
-- ----------------------------
DROP TABLE IF EXISTS `t_group_member`;
CREATE TABLE `t_group_member` (
  `t_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_user_id` int(11) NOT NULL,
  PRIMARY KEY (`t_group_id`,`t_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_group_member
-- ----------------------------

-- ----------------------------
-- Table structure for t_menus
-- ----------------------------
DROP TABLE IF EXISTS `t_menus`;
CREATE TABLE `t_menus` (
  `t_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_menu_name` varchar(200) DEFAULT NULL,
  `t_menu_url` varchar(200) DEFAULT NULL,
  `t_p_id` int(11) DEFAULT NULL,
  `t_menu_icon` varchar(200) DEFAULT NULL,
  `t_createtime` datetime DEFAULT NULL,
  `spare1` varchar(200) DEFAULT NULL,
  `spare2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`t_menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_menus
-- ----------------------------
INSERT INTO `t_menus` VALUES ('1', '设备管理', null, null, null, null, null, null);
INSERT INTO `t_menus` VALUES ('2', '设备调度管理', null, null, null, null, null, null);
INSERT INTO `t_menus` VALUES ('3', '团队管理', null, null, null, null, null, null);
INSERT INTO `t_menus` VALUES ('4', '系统管理', null, null, null, null, null, null);
INSERT INTO `t_menus` VALUES ('5', '问题反馈', null, null, null, null, null, null);
INSERT INTO `t_menus` VALUES ('6', '设备信息', null, '1', null, null, null, null);
INSERT INTO `t_menus` VALUES ('7', '设备分类', null, '1', null, null, null, null);
INSERT INTO `t_menus` VALUES ('8', '设备维修', null, '1', null, null, null, null);
INSERT INTO `t_menus` VALUES ('9', '设备权限申请', null, '2', null, null, null, null);
INSERT INTO `t_menus` VALUES ('10', '设备权限管理', null, '2', null, null, null, null);
INSERT INTO `t_menus` VALUES ('11', '设备借出', null, '2', null, null, null, null);
INSERT INTO `t_menus` VALUES ('12', '设备预约', null, '2', null, null, null, null);
INSERT INTO `t_menus` VALUES ('13', '设备流转记录', null, '2', null, null, null, null);
INSERT INTO `t_menus` VALUES ('14', '团队信息', null, '3', null, null, null, null);
INSERT INTO `t_menus` VALUES ('15', '团队申请', null, '3', null, null, null, null);
INSERT INTO `t_menus` VALUES ('16', '我的团队', null, '3', null, null, null, null);
INSERT INTO `t_menus` VALUES ('17', '角色管理', null, '4', null, null, null, null);
INSERT INTO `t_menus` VALUES ('18', '权限管理', null, '4', null, null, null, null);
INSERT INTO `t_menus` VALUES ('19', '部门管理', null, '4', null, null, null, null);
INSERT INTO `t_menus` VALUES ('20', '用户信息', null, '4', null, null, null, null);
INSERT INTO `t_menus` VALUES ('21', '反馈信息', null, '5', null, null, null, null);
INSERT INTO `t_menus` VALUES ('22', '问题反馈', null, '5', null, null, null, null);
INSERT INTO `t_menus` VALUES ('23', '错误日志管理', null, null, null, '2021-02-03 10:46:22', null, null);

-- ----------------------------
-- Table structure for t_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `t_role_menu`;
CREATE TABLE `t_role_menu` (
  `t_role_id` int(11) NOT NULL,
  `t_menu_id` int(11) NOT NULL,
  PRIMARY KEY (`t_role_id`,`t_menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_role_menu
-- ----------------------------
INSERT INTO `t_role_menu` VALUES ('1', '1');
INSERT INTO `t_role_menu` VALUES ('1', '2');
INSERT INTO `t_role_menu` VALUES ('1', '3');

-- ----------------------------
-- Table structure for t_roles
-- ----------------------------
DROP TABLE IF EXISTS `t_roles`;
CREATE TABLE `t_roles` (
  `t_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_role_name` varchar(22) DEFAULT NULL,
  `spare1` varchar(200) DEFAULT NULL,
  `spare2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`t_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_roles
-- ----------------------------
INSERT INTO `t_roles` VALUES ('1', '管理员', null, null);

-- ----------------------------
-- Table structure for t_subscribe
-- ----------------------------
DROP TABLE IF EXISTS `t_subscribe`;
CREATE TABLE `t_subscribe` (
  `t_subscribe_device_rfid_num` varchar(22) DEFAULT NULL,
  `t_subscribe_user_num` varchar(22) DEFAULT NULL,
  `t_subscribe_rank` int(11) NOT NULL AUTO_INCREMENT,
  `t_subscribe_creatTime` datetime DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  `t_subscribe_state` int(2) DEFAULT '1' COMMENT '1有效0无效',
  PRIMARY KEY (`t_subscribe_rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_subscribe
-- ----------------------------

-- ----------------------------
-- Table structure for t_suggest
-- ----------------------------
DROP TABLE IF EXISTS `t_suggest`;
CREATE TABLE `t_suggest` (
  `t_suggest_id` int(11) NOT NULL,
  `t_suggest_type` varchar(200) DEFAULT NULL COMMENT '建议类型',
  `t_user_id` int(11) DEFAULT NULL,
  `t_suggestion` text,
  `spare1` varchar(200) DEFAULT NULL,
  `spare2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`t_suggest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_suggest
-- ----------------------------

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `t_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_user_num` varchar(22) NOT NULL COMMENT '员工编号',
  `t_user_name` varchar(22) DEFAULT NULL,
  `t_password` varchar(22) DEFAULT NULL,
  `t_user_sex` int(2) DEFAULT NULL,
  `t_user_dept_id` int(11) DEFAULT NULL,
  `t_role_id` int(11) DEFAULT NULL,
  `t_user_telphone` varchar(22) DEFAULT NULL,
  `t_user_photo` varchar(255) DEFAULT NULL,
  `t_reg_time` datetime DEFAULT NULL,
  `spare1` varchar(255) DEFAULT NULL,
  `spare2` varchar(255) DEFAULT NULL,
  `t_user_state` int(2) DEFAULT NULL COMMENT '员工状态',
  `t_user_del` int(2) DEFAULT '1',
  PRIMARY KEY (`t_user_id`),
  UNIQUE KEY `uni_t_user_num` (`t_user_num`),
  UNIQUE KEY `uni_t_user_name` (`t_user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('1', '123', 'admin', '123', '1', '1', null, null, null, null, null, null, null, '1');
INSERT INTO `t_user` VALUES ('2', '1234', 'user', '123', '0', '2', null, null, null, null, null, null, null, '1');
INSERT INTO `t_user` VALUES ('4', '12345', 'admin1', '123', '1', '1', '1', null, null, null, null, null, null, '1');
