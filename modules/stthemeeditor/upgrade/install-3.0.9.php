<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_0_9($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_NAVIGATION_PIPE', Configuration::get('PS_NAVIGATION_PIPE'));
    
	return $result;
}
