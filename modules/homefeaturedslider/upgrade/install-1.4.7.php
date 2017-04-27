<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_7($object)
{
    $result = true;

    $result &= $object->registerHook('displayHeader');

    $result &= Configuration::updateValue('HOME_FEATURED_S_TOP_PADDING', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_BOTTOM_PADDING', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_TOP_MARGIN', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_BOTTOM_MARGIN', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_BG_PATTERN', 0);
    $result &= Configuration::updateValue('HOME_FEATURED_S_BG_IMG', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_BG_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_SPEED', 0);
    $result &= Configuration::updateValue('HOME_FEATURED_S_TITLE_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_TEXT_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_PRICE_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_LINK_HOVER_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_GRID_HOVER_BG', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_DIRECTION_COLOR', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_DIRECTION_BG', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_DIRECTION_HOVER_BG', '');
    $result &= Configuration::updateValue('HOME_FEATURED_S_DIRECTION_DISABLED_BG', '');

    $result &= Configuration::updateValue('HOME_FEATURED_S_TITLE_ALIGNMENT', 0);
    $result &= Configuration::updateValue('HOME_FEATURED_S_TITLE_FONT_SIZE', 0);
    $result &= Configuration::updateValue('HOME_FEATURED_S_DIRECTION_NAV', 0);
            
	return $result;
}
