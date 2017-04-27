<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_7($object)
{
    $result = true;
	$result &= Configuration::updateValue('STSN_FEATURED_CATE_PER_LG_0', 4);
	$result &= Configuration::updateValue('STSN_FEATURED_CATE_PER_MD_0', 4);
	$result &= Configuration::updateValue('STSN_FEATURED_CATE_PER_SM_0', 3);
	$result &= Configuration::updateValue('STSN_FEATURED_CATE_PER_XS_0', 2);
	$result &= Configuration::updateValue('STSN_FEATURED_CATE_PER_XXS_0', 1);
	return $result;
}
