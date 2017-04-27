<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_7($object)
{
    $result = true;

    $result &= $object->registerHook('displayHeader');

    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_TOP_PADDING', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_BOTTOM_PADDING', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_TOP_MARGIN', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_BOTTOM_MARGIN', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_BG_PATTERN', 0);
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_BG_IMG', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_BG_COLOR', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_SPEED', 0);
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_TITLE_COLOR', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_TITLE_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_TEXT_COLOR', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_PRICE_COLOR', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_LINK_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_GRID_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_DIRECTION_COLOR', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_DIRECTION_BG', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_DIRECTION_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_DIRECTION_DISABLED_BG', '');

    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_TITLE_ALIGNMENT', 0);
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_TITLE_FONT_SIZE', 0);
    $result &= Configuration::updateValue('ST_SPECIAL_SLIDER_DIRECTION_NAV', 0);

	return $result;
}
