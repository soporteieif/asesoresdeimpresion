<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_5($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayShoppingCartFooter');

    $maps = array(
        13  => array(
            3 => 13,
            4 => 39,
            6 => 41,
            9 => 73,
            12 => 42
        ),
        3   => array(
            3 => 3,
            4 => 44,
            6 => 46,
            9 => 83,
            12 => 47
        ),
        12  => array(
            3 => 12,
            4 => 49,
            6 => 51,
            9 => 93,
            12 => 52
        ),
    );

    $res = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'st_easy_content`');  
    foreach($res AS $value)
        if (key_exists($value['location'], $maps) && key_exists($value['span'], $maps[$value['location']]))
            $result &= Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'st_easy_content` SET `location` = '.(int)$maps[$value['location']][$value['span']].', `span` = 0 WHERE `id_st_easy_content` = '.(int)$value['id_st_easy_content']);
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_easy_content` `top_spacing`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_easy_content` 
        ADD `top_spacing` varchar(10) DEFAULT NULL,
        ADD `bottom_spacing` varchar(10) DEFAULT NULL,
        ADD `bg_pattern` tinyint(2) unsigned NOT NULL DEFAULT 0, 
        ADD `bg_img` varchar(255) DEFAULT NULL,
        ADD `speed` float(4,1) unsigned NOT NULL DEFAULT 0.1'))
        $result &= false;

	return $result;
}
