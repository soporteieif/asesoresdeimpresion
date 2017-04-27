<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_6($object)
{
    $result = true;
    
    $data = Db::getInstance()->executeS('SELECT * from '._DB_PREFIX_.'st_iosslider_lang');
    foreach($data AS $row)
    {
        if (preg_match('/^(.*\/upload\/)+/iS', $row['image_multi_lang'], $match))
        {
            if (isset($match[1]))
            {
                $row['image_multi_lang'] = str_replace($match[1], '', $row['image_multi_lang']);
                $row['thumb_multi_lang'] = str_replace($match[1], '', $row['thumb_multi_lang']);
                $result &= Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'st_iosslider_lang SET `image_multi_lang` = "'.$row['image_multi_lang'].'",`thumb_multi_lang` = "'.$row['thumb_multi_lang'].'" WHERE id_st_iosslider='.(int)$row['id_st_iosslider'].' && id_lang='.(int)$row['id_lang']);  
            }
        }
    }
            
    return $result;
}