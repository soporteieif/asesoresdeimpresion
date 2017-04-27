<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_6($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_blog_lang` `author`');  
      
    if(!is_array($field) || !count($field))
        $result = Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_blog_lang` ADD `author` varchar(64) default NULL');
    
    return $result;
}
