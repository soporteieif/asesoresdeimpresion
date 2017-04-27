<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_9($object)
{
    $result = true;
	$result &= Configuration::updateValue('HOME_FEATURED_S_DISPLAY_SD', 0);
	$result &= Configuration::updateValue('HOME_FEATURED_S_GRID', 0);
	$result &= Configuration::updateValue('STSN_FEATURED_PRO_PER_LG_0', Configuration::get('HOME_FEATURED_S_ITEMS'));
	$result &= Configuration::updateValue('STSN_FEATURED_PRO_PER_MD_0', Configuration::get('HOME_FEATURED_S_ITEMS'));
	$result &= Configuration::updateValue('STSN_FEATURED_PRO_PER_SM_0', 3);
	$result &= Configuration::updateValue('STSN_FEATURED_PRO_PER_XS_0', 2);
	$result &= Configuration::updateValue('STSN_FEATURED_PRO_PER_XXS_0', 1);
	return $result;
}
