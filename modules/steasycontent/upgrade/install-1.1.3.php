<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_3($object)
{
    $result = true;
        
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_easy_content` `span`');  
   
    if(is_array($field) && count($field))
        return $result;

    if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_easy_content` ADD `span` tinyint(2) unsigned NOT NULL DEFAULT 0'))
		$result = false;
                    
    $result &= $object->registerHook('displayFooterProduct') 
    && $object->registerHook('displayCategoryHeader') 
    && $object->registerHook('displayCategoryFooter');
    
	return $result;
}
