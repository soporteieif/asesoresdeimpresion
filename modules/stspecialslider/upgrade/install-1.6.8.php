<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_8($object)
{
    $result = true;
    
	$result &= Configuration::updateValue('ST_SPECIAL_SLIDER_DISPLAY_SD', 0);
	$result &= Configuration::updateValue('ST_SPECIAL_SLIDER_AW_DISPLAY', 1);
	$result &= Configuration::updateValue('ST_SPECIAL_S_AW_DISPLAY_COL', 1);
	$result &= Configuration::updateValue('ST_SPECIAL_S_AW_DISPLAY_FOT', 1);
	$result &= Configuration::updateValue('ST_SPECIAL_SLIDER_GRID', 0);
	$result &= Configuration::updateValue('STSN_SPECIAL_PRO_PER_LG_0', Configuration::get('ST_SPECIAL_SLIDER_ITEMS'));
	$result &= Configuration::updateValue('STSN_SPECIAL_PRO_PER_MD_0', Configuration::get('ST_SPECIAL_SLIDER_ITEMS'));
	$result &= Configuration::updateValue('STSN_SPECIAL_PRO_PER_SM_0', 3);
	$result &= Configuration::updateValue('STSN_SPECIAL_PRO_PER_XS_0', 2);
	$result &= Configuration::updateValue('STSN_SPECIAL_PRO_PER_XXS_0', 1);

	$result &= Configuration::updateValue('ST_SPECIAL_S_NBR_TAB', 8);
	$result &= Configuration::updateValue('ST_SPECIAL_S_SOBY_TAB', 7);

	return $result;
}
