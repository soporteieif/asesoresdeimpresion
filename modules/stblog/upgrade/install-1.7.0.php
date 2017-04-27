<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_0($object)
{
	$result = true;
    $languages = Language::getLanguages(false);
	$route = array();
	foreach ($languages as $language)
	{
        $route[$language['id_lang']] = 'blog';
	}
    foreach(Shop::getShops(true, null, true) AS $id_shop)
        if (!Shop::isFeatureActive())
            $result &= Configuration::updateValue('ST_BLOG_ROUNT_NAME', $route);
        else
            $result &= Configuration::updateValue('ST_BLOG_ROUNT_NAME', $route, false, null, (int)$id_shop);
    
    return $result;
}