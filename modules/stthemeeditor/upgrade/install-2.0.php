<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_0($object)
{
    $result = true;
	$result &= Configuration::updateGlobalValue('STSN_GOOGLE_RICH_SNIPPETS','1');
        Configuration::updateGlobalValue('STSN_DISPLAY_TAX_LABEL','0');
        Configuration::updateGlobalValue('STSN_POSITION_RIGHT_PANEL','1_40');
        
	return $result;
}
