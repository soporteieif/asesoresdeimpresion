<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_9($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_multi_link_group` `hide_on_mobile`');  
       
    if(!is_array($field) || !count($field))
    {
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_multi_link_group` ADD `hide_on_mobile` tinyint(1) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
        if($result)
        {
            $old = Db::getInstance()->executeS('
        			SELECT `id_st_multi_link_group`
        			FROM `'._DB_PREFIX_.'st_multi_link_group` 
                    WHERE `location`=1');
            if(is_array($old) && count($old))
                foreach($old as $v)
                {
                    if(!$result)
                        break;
                    $result &= Db::getInstance()->execute('
                       UPDATE `'._DB_PREFIX_.'st_multi_link_group` 
                       SET `hide_on_mobile`=1 
                       WHERE `id_st_multi_link_group`='.(int)$v['id_st_multi_link_group']);
                }
        }
    }
        
    return $result;
}
