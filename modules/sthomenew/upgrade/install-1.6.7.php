<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_7($object)
{
    $result = true;
	$result &= Configuration::updateValue('ST_HOMENEW_DISPLAY_SD', 0);
	$result &= Configuration::updateValue('ST_HOMENEW_AW_DISPLAY', 1);
	$result &= Configuration::updateValue('ST_HOMENEW_AW_DISPLAY_COL', 1);
	$result &= Configuration::updateValue('ST_HOMENEW_AW_DISPLAY_FOT', 1);
	$result &= Configuration::updateValue('ST_HOMENEW_GRID', 0);
	$result &= Configuration::updateValue('STSN_HOMENEW_PRO_PER_LG_0', Configuration::get('ST_HOMENEW_ITEMS'));
	$result &= Configuration::updateValue('STSN_HOMENEW_PRO_PER_MD_0', Configuration::get('ST_HOMENEW_ITEMS'));
	$result &= Configuration::updateValue('STSN_HOMENEW_PRO_PER_SM_0', 3);
	$result &= Configuration::updateValue('STSN_HOMENEW_PRO_PER_XS_0', 2);
	$result &= Configuration::updateValue('STSN_HOMENEW_PRO_PER_XXS_0', 1);
	return $result;
}
