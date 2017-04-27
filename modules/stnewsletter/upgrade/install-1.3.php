<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_news_letter` `display_on`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_news_letter` 
        ADD `display_on` int(10) unsigned NOT NULL DEFAULT 0'))
        $result &= false;
    
    if (!Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'st_news_letter` set display_on = 1'))
        $result &= false;

	return $result;
}
