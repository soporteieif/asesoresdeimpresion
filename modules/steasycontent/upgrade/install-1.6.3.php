<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_3($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayFullWidthTop');
    $result &= $object->registerHook('displayTopColumn');
    $result &= $object->registerHook('displayBottomColumn');
    $result &= $object->registerHook('displayHeader');

    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_easy_content` `text_color`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_easy_content` 
        ADD `text_color` varchar(7) DEFAULT NULL,
        ADD `link_color` varchar(7) DEFAULT NULL,
        ADD `link_hover` varchar(7) DEFAULT NULL,
        ADD `text_bg` varchar(7) DEFAULT NULL,
        ADD `text_align` tinyint(1) unsigned NOT NULL DEFAULT 1,
        ADD `margin_top` int(10) unsigned NOT NULL DEFAULT 0,
        ADD `margin_bottom` int(10) unsigned NOT NULL DEFAULT 0,
        ADD `width` tinyint(2) unsigned NOT NULL DEFAULT 0,
        ADD `btn_color` varchar(7) DEFAULT NULL,
        ADD `btn_bg` varchar(7) DEFAULT NULL,
        ADD `btn_hover_color` varchar(7) DEFAULT NULL,
        ADD `btn_hover_bg` varchar(7) DEFAULT NULL'))
		$result = false;
	
    if (!Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_easy_content_font` (
            `id_st_easy_content` int(10) unsigned NOT NULL,
            `font_name` varchar(255) NOT NULL
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'))
        $result = false;

	return $result;
}
