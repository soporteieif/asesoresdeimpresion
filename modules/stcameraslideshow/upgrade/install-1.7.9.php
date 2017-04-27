<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_9($object)
{
    $result = true;
    
    $data = Db::getInstance()->executeS('SELECT * from '._DB_PREFIX_.'st_camera_slideshow');
    foreach($data AS $row)
    {
        if (preg_match('/^(.*\/upload\/)+/iS', $row['image'], $match))
        {
            if (isset($match[1]))
            {
                $row['image'] = str_replace($match[1], '', $row['image']);
                $row['thumb'] = str_replace($match[1], '', $row['thumb']);
                $result &= Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'st_camera_slideshow SET `image` = "'.$row['image'].'",`thumb` = "'.$row['thumb'].'" WHERE id_st_camera_slideshow='.(int)$row['id_st_camera_slideshow']);
            }
        }
    }
    $data = Db::getInstance()->executeS('SELECT * from '._DB_PREFIX_.'st_camera_slideshow_lang');
    foreach($data AS $row)
    {
        if (preg_match('/^(.*\/upload\/)+/iS', $row['image_multi_lang'], $match))
        {
            if (isset($match[1]))
            {
                $row['image_multi_lang'] = str_replace($match[1], '', $row['image_multi_lang']);
                $row['thumb_multi_lang'] = str_replace($match[1], '', $row['thumb_multi_lang']);
                $result &= Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'st_camera_slideshow_lang SET `image_multi_lang` = "'.$row['image_multi_lang'].'",`thumb_multi_lang` = "'.$row['thumb_multi_lang'].'" WHERE id_st_camera_slideshow='.(int)$row['id_st_camera_slideshow'].' && id_lang='.(int)$row['id_lang']);  
            }
        }
    }
            
    return $result;
}