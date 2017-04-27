<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_0_5($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_SOLD_OUT', 0);
    $result &= Configuration::updateGlobalValue('STSN_SOLD_OUT_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_SOLD_OUT_BG_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_SOLD_OUT_BG_IMG', '');
    $result &= Configuration::updateGlobalValue('STSN_RETINA', 0);
    $result &= Configuration::updateGlobalValue('STSN_YOTPO_STAR', 0);
    $result &= Configuration::updateGlobalValue('STSN_RETINA_LOGO', '');
    
	return $result;
}
