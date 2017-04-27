<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_4($object)
{
    $result = true;

    $result &= $object->registerHook('displayHeader');

    $result &= Configuration::updateValue('HOME_FEATURED_TOP_PADDING', '');
    $result &= Configuration::updateValue('HOME_FEATURED_BOTTOM_PADDING', '');
    $result &= Configuration::updateValue('HOME_FEATURED_TOP_MARGIN', '');
    $result &= Configuration::updateValue('HOME_FEATURED_BOTTOM_MARGIN', '');
    $result &= Configuration::updateValue('HOME_FEATURED_BG_PATTERN', 0);
    $result &= Configuration::updateValue('HOME_FEATURED_BG_IMG', '');
    $result &= Configuration::updateValue('HOME_FEATURED_BG_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_SPEED', 0);
    $result &= Configuration::updateValue('HOME_FEATURED_TITLE_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_TEXT_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_PRICE_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_LINK_HOVER_COLOR', '');

	return $result;
}
