<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_4($object)
{
    $result = true;

    $result &= Configuration::updateValue('BRANDS_S_ITEMS_COL', (int)Configuration::get('BRANDS_SLIDER_ITEMS'));
    $result &= Configuration::updateValue('BRANDS_S_SLIDESHOW_COL', 0);
    $result &= Configuration::updateValue('BRANDS_S_S_SPEED_COL', 7000);
    $result &= Configuration::updateValue('BRANDS_S_A_SPEED_COL', 400);
    $result &= Configuration::updateValue('BRANDS_S_PAUSE_ON_HOVER_COL', 1);
    $result &= Configuration::updateValue('BRANDS_S_EASING_COL', 0);
    $result &= Configuration::updateValue('BRANDS_S_LOOP_COL', 0);

	$result &= Configuration::updateValue('STSN_BRANDS_PRO_PER_LG_0', (int)Configuration::get('BRANDS_SLIDER_ITEMS'));
	$result &= Configuration::updateValue('STSN_BRANDS_PRO_PER_MD_0', (int)Configuration::get('BRANDS_SLIDER_ITEMS'));
	$result &= Configuration::updateValue('STSN_BRANDS_PRO_PER_SM_0', 4);
	$result &= Configuration::updateValue('STSN_BRANDS_PRO_PER_XS_0', 3);
	$result &= Configuration::updateValue('STSN_BRANDS_PRO_PER_XXS_0', 2);

	return $result;
}
