<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_6_6($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_PRO_TAGS', 0);
    $result &= Configuration::updateGlobalValue('STSN_FONT_OLD_PRICE_SIZE', 0);
    $result &= Configuration::updateGlobalValue('STSN_STICKY_MENU', 0);
    $result &= Configuration::updateGlobalValue('STSN_VERSION_SWITCHING', 0);
    
	return $result;
}
