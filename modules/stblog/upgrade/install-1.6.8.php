<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_8($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_blog_product_link` `active`');
      
    if(is_array($field) && count($field))
    {
        $result &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_blog_product_link` CHANGE `active` `id_shop` int(10) unsigned NOT NULL DEFAULT 0');
        $result &= Db::getInstance()->Execute('DROP INDEX `PRIMARY` ON `'._DB_PREFIX_.'st_blog_product_link`');
    }
    
    $blog_images = array(
        'gallery_lg_w'  => 870,
        'gallery_lg_h'  => 348,
        'gallery_md_w'  => 580,
        'gallery_md_h'  => 324,
        'gallery_sm_w'  => 100,
        'gallery_sm_h'  => 100,
        'gallery_xs_w'  => 56,
        'gallery_xs_h'  => 56,
    );
    foreach(Shop::getShops(true, null, true) AS $id_shop)
        foreach($blog_images AS $key => $value)
            if (!Shop::isFeatureActive())
                $result &= Configuration::updateValue('ST_BLOG_IMG_'.strtoupper($key), (int)$value);
            else
                $result &= Configuration::updateValue('ST_BLOG_IMG_'.strtoupper($key), (int)$value, false, null, (int)$id_shop);
    
    return $result;
}