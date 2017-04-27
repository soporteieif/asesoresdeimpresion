<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_9($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayHeader');
    
    $result &= Configuration::updateValue('ST_PRO_CATE_TOP_PADDING', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_BOTTOM_PADDING', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_TOP_MARGIN', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_BOTTOM_MARGIN', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_BG_PATTERN', 0);
    $result &= Configuration::updateValue('ST_PRO_CATE_BG_IMG', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_BG_COLOR', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_SPEED', 0);
    $result &= Configuration::updateValue('ST_PRO_CATE_TITLE_COLOR', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_TITLE_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_TEXT_COLOR', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_PRICE_COLOR', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_LINK_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_GRID_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_DIRECTION_COLOR', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_DIRECTION_BG', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_DIRECTION_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_PRO_CATE_DIRECTION_DISABLED_BG', '');
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_product_categories_slider` `top_spacing`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_product_categories_slider` 
        ADD `top_spacing` varchar(10) DEFAULT NULL,
        ADD `bottom_spacing` varchar(10) DEFAULT NULL,
        ADD `top_padding` varchar(10) DEFAULT NULL,
        ADD `bottom_padding` varchar(10) DEFAULT NULL,
        ADD `bg_pattern` tinyint(2) unsigned NOT NULL DEFAULT 0, 
        ADD `bg_img` varchar(255) DEFAULT NULL,
        ADD `bg_color` varchar(7) DEFAULT NULL,
        ADD `speed` float(4,1) unsigned NOT NULL DEFAULT 0.1,
        ADD `title_color` varchar(7) DEFAULT NULL,
        ADD `title_hover_color` varchar(7) DEFAULT NULL,
        ADD `text_color` varchar(7) DEFAULT NULL,
        ADD `price_color` varchar(7) DEFAULT NULL,
        ADD `grid_hover_bg` varchar(7) DEFAULT NULL,
        ADD `link_hover_color` varchar(7) DEFAULT NULL,
        ADD `direction_color` varchar(7) DEFAULT NULL,
        ADD `direction_bg` varchar(7) DEFAULT NULL,
        ADD `direction_hover_bg` varchar(7) DEFAULT NULL,
        ADD `direction_disabled_bg` varchar(7) DEFAULT NULL,
        ADD `title_alignment` tinyint(1) unsigned NOT NULL DEFAULT 0, 
        ADD `title_font_size` int(10) unsigned NOT NULL DEFAULT 0, 
        ADD `direction_nav` tinyint(1) unsigned NOT NULL DEFAULT 0'))
        $result &= false;
    
    return $result;
}