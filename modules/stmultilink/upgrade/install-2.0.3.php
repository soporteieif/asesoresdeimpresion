<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_0_3($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_multi_link_group` `link_align`');  
       
    if(!is_array($field) || !count($field))
    {
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_multi_link_group` ADD `link_align` tinyint(1) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
    }
    
    return $result;
}
