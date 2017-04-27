<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_2($object)
{
    $result = true;
    
    $result &= Configuration::updateValue('ST_FB_LB_COLORSCHEME', 'light');
    
    return $result;
}
