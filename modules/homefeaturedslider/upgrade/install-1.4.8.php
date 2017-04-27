<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_8($object)
{
    $result = true;
    
    $result &= Configuration::updateValue('HOME_FEATURED_S_COUNTDOWN_ON', 1);
            
	return $result;
}
