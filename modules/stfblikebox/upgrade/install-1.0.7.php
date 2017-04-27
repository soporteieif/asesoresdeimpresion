<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_7($object)
{
    $result = true;
    
    $result &= Configuration::updateValue('ST_FB_LB_URL', 'https://www.facebook.com/'.Configuration::get('ST_FB_LB_URL'));
    $result &= Configuration::updateValue('ST_FB_LB_HIDE_COVER', 0);
    $result &= Configuration::updateValue('ST_FB_LB_SMALL_HEADER', 1);
    $result &= Configuration::updateValue('ST_FB_LB_STREAM', 1);
    
    return $result;
}
