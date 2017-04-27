<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_1($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_banner_lang` `image_multi_lang`');  
       
    if(!is_array($field) || !count($field))
    {
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_banner` ADD `id_currency` int(10) unsigned DEFAULT 0') 
        || !Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_banner_lang` ADD `image_multi_lang` VARCHAR(255) DEFAULT NULL'))
    		$result &= false;
        if($result)
        {
            $old = Db::getInstance()->executeS('
        			SELECT `id_st_banner`, `image`
        			FROM `'._DB_PREFIX_.'st_banner`');
            if(is_array($old) && count($old))
                foreach($old as $v)
                {
                    if(!$result)
                        break;
                    $result &= Db::getInstance()->execute('
                       UPDATE `'._DB_PREFIX_.'st_banner_lang` 
                       SET `image_multi_lang`="'.$v['image'].'" 
                       WHERE `id_st_banner`='.(int)$v['id_st_banner']);
                }
        }
    }
        
    
    return $result;
}
