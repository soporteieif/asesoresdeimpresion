<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_8($object)
{
    $result = true;
    $data = Db::getInstance()->executeS('SELECT * from '._DB_PREFIX_.'st_banner');
    foreach($data AS $row)
    {
        if (preg_match('/^(.*\/upload\/)+/iS', $row['image'], $match))
        {
            if (isset($match[1]))
            {
                $row['image'] = str_replace($match[1], '', $row['image']);
                $result &= Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'st_banner SET `image` = "'.$row['image'].'" WHERE id_st_banner='.(int)$row['id_st_banner']);  
            }
        }
    }
    $data = Db::getInstance()->executeS('SELECT * from '._DB_PREFIX_.'st_banner_lang');
    foreach($data AS $row)
    {
        if (preg_match('/^(.*\/upload\/)+/iS', $row['image_multi_lang'], $match))
        {
            if (isset($match[1]))
            {
                $row['image_multi_lang'] = str_replace($match[1], '', $row['image_multi_lang']);
                $result &= Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'st_banner_lang SET `image_multi_lang` = "'.$row['image_multi_lang'].'" WHERE id_st_banner='.(int)$row['id_st_banner'].' && id_lang='.(int)$row['id_lang']);  
            }
        }
    }
            
    return $result;
}