<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_6($object)
{
    $result = true;

	$result &= Configuration::updateValue('STSN_RELATED_PRO_PER_LG_0', (int)Configuration::get('ST_RELATED_ITEMS'));
	$result &= Configuration::updateValue('STSN_RELATED_PRO_PER_MD_0', (int)Configuration::get('ST_RELATED_ITEMS'));
	$result &= Configuration::updateValue('STSN_RELATED_PRO_PER_SM_0', 4);
	$result &= Configuration::updateValue('STSN_RELATED_PRO_PER_XS_0', 3);
	$result &= Configuration::updateValue('STSN_RELATED_PRO_PER_XXS_0', 2);

	return $result;
}
