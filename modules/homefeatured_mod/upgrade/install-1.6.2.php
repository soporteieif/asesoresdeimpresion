<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_2($object)
{
	return Configuration::updateValue('HOME_FEATURED_CAT_MOD', (int)Context::getContext()->shop->getCategory());
}
