<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_2($object)
{
    $result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_camera_slideshow_lang` `image_multi_lang`');  
       
    if(!is_array($field) || !count($field))
    {
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_camera_slideshow` ADD `id_currency` int(10) unsigned DEFAULT 0') 
        || !Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_camera_slideshow_lang` ADD `image_multi_lang` VARCHAR(255) DEFAULT NULL,ADD `thumb_multi_lang` VARCHAR(255) DEFAULT NULL'))
    		$result &= false;
        
        if($result)
        {
            $old = Db::getInstance()->executeS('
    			SELECT `id_st_camera_slideshow`, `image`, `thumb`
    			FROM `'._DB_PREFIX_.'st_camera_slideshow`');
            if(is_array($old) && count($old))
                foreach($old as $v)
                {
                    if(!$result)
                        break;
                    $result &= Db::getInstance()->execute('
                       UPDATE `'._DB_PREFIX_.'st_camera_slideshow_lang` 
                       SET `image_multi_lang`="'.$v['image'].'" ,`thumb_multi_lang`="'.$v['thumb'].'"  
                       WHERE `id_st_camera_slideshow`='.(int)$v['id_st_camera_slideshow']);
                }
        }
    }
    
    $result &= $object->registerHook('displayFooterProduct') 
    && $object->registerHook('displayCategoryFooter');
    
    return $result;
}
