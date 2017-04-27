<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_7($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_blog` `accept_comment`');  
      
    if(!is_array($field) || !count($field))
    {
        return Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_blog` ADD `accept_comment` tinyint(1) unsigned NOT NULL DEFAULT 1');
        
    }   
    
    return $result;
}
