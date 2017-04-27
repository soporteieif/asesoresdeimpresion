<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();
$sql[_DB_PREFIX_.'st_blog'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog` (
`id_st_blog` int(10) NOT NULL AUTO_INCREMENT,
`status` tinyint(2) unsigned NOT NULL DEFAULT 0,
`comments_status` tinyint(1) unsigned NOT NULL DEFAULT 0,
`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
`type` tinyint(1) unsigned NOT NULL DEFAULT 1,
`position` int(10) unsigned NOT NULL DEFAULT 0,
`id_st_blog_category_default` int(10) unsigned DEFAULT NULL,
`counter` int(10) unsigned NOT NULL DEFAULT 0,
`date_add` datetime NOT NULL,
`date_upd` datetime NOT NULL,
`accept_comment` tinyint(1) unsigned NOT NULL DEFAULT 1,
PRIMARY KEY (`id_st_blog`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_lang'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_lang` (
`id_st_blog` int(10) unsigned NOT NULL,
`id_lang` int(10) unsigned NOT NULL,
`name` varchar(128) NOT NULL,
`meta_title` varchar(128) NOT NULL,
`meta_description` varchar(255) default NULL,
`meta_keywords` varchar(255) default NULL,
`content` longtext,
`content_short` text,
`link_rewrite` varchar(128) NOT NULL,
`video` text default NULL,
`author` varchar(64) default NULL,
PRIMARY KEY (`id_st_blog`,`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_product_link'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_product_link` (
`id_st_blog` int(10) unsigned NOT NULL,
`id_product` int(10) unsigned NOT NULL,
`id_shop` int(10) unsigned NOT NULL DEFAULT 0,
`position` int(10) unsigned NOT NULL DEFAULT 0
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_shop'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_shop` (
`id_st_blog` INT( 11 ) UNSIGNED NOT NULL,
`id_shop` INT( 11 ) UNSIGNED NOT NULL,
`active` tinyint(1) unsigned NOT NULL DEFAULT 1, 
`id_st_blog_category_default` int(10) unsigned DEFAULT NULL,
`counter` int(10) unsigned NOT NULL DEFAULT 0,
PRIMARY KEY (`id_st_blog`, `id_shop`),
KEY `id_shop` (`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_category'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_category` (
`id_st_blog_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_parent` int(10) unsigned NOT NULL,
`level_depth` tinyint(3) unsigned NOT NULL DEFAULT 0,
`path` varchar(255) DEFAULT NULL,
`nleft` int(10) unsigned NOT NULL DEFAULT 0,            
`nright` int(10) unsigned NOT NULL DEFAULT 0,           
`active` tinyint(1) unsigned NOT NULL DEFAULT 0,
`is_root_category` tinyint(1) unsigned NOT NULL DEFAULT 0,
`date_add` datetime NOT NULL,
`date_upd` datetime NOT NULL,
`position` int(10) unsigned NOT NULL default 0,
PRIMARY KEY (`id_st_blog_category`),
KEY `category_parent` (`id_parent`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_category_lang'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_category_lang` (
`id_st_blog_category` int(10) unsigned NOT NULL,
`id_lang` int(10) unsigned NOT NULL,
`name` varchar(128) NOT NULL,
`description` text,
`link_rewrite` varchar(128) NOT NULL,
`meta_title` varchar(128) DEFAULT NULL,
`meta_keywords` varchar(255) DEFAULT NULL,
`meta_description` varchar(255) DEFAULT NULL,
PRIMARY KEY (`id_st_blog_category`,`id_lang`),
KEY `category_name` (`name`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_category_blog'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_category_blog` (
`id_st_blog_category` int(10) unsigned NOT NULL,
`id_st_blog` int(10) unsigned NOT NULL,
`position` int(10) unsigned NOT NULL default 0,
PRIMARY KEY (`id_st_blog_category`,`id_st_blog`),          
KEY `id_st_blog` (`id_st_blog`)  
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_category_shop'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_category_shop` (
`id_st_blog_category` INT( 11 ) UNSIGNED NOT NULL,
`id_shop` INT( 11 ) UNSIGNED NOT NULL ,
`position` int(10) unsigned NOT NULL default 0,
PRIMARY KEY (`id_st_blog_category`, `id_shop`),
KEY `id_shop` (`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_tag_map'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_tag_map` (
`id_st_blog_tag` int(10) unsigned NOT NULL,
`id_st_blog` int(10) unsigned NOT NULL,
PRIMARY KEY (`id_st_blog_tag`,`id_st_blog`),
KEY `id_st_blog_tag` (`id_st_blog_tag`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_tag'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_tag` (
`id_st_blog_tag` int(10) NOT NULL AUTO_INCREMENT,
`id_lang` int(10) unsigned NOT NULL,
`name` varchar(32) NOT NULL,
PRIMARY KEY (`id_st_blog_tag`),
KEY `tag_name` (`name`),
KEY `id_lang` (`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


$sql[_DB_PREFIX_.'st_blog_image'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_image` (
   `id_st_blog_image` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `id_st_blog` int(10) unsigned NOT NULL,
   `type` tinyint(3) unsigned DEFAULT 1,
   `position` smallint(2) unsigned NOT NULL DEFAULT 0,
   PRIMARY KEY (`id_st_blog_image`),
   KEY `image_blog` (`id_st_blog`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_image_lang'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_image_lang` (
   `id_st_blog_image` int(10) unsigned NOT NULL,
   `id_lang` int(10) unsigned NOT NULL,
   PRIMARY KEY (`id_st_blog_image`,`id_lang`),
   KEY `id_st_blog_image` (`id_st_blog_image`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[_DB_PREFIX_.'st_blog_image_shop'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_blog_image_shop` (
   `id_st_blog_image` int(11) unsigned NOT NULL,
   `id_shop` int(11) unsigned NOT NULL,
   KEY `id_st_blog_image` (`id_st_blog_image`,`id_shop`),
   KEY `id_shop` (`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';