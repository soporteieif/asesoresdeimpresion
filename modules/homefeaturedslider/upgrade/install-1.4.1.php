<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_1($object)
{
	return Configuration::updateValue('HOME_FEATURED_S_CAT', (int)Context::getContext()->shop->getCategory());
}
