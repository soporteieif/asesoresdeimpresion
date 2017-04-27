<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_7($object)
{
    $result = true;

    $result &= $object->registerHook('displayHeader');

    $result &= Configuration::updateValue('ST_SELLERS_TOP_PADDING', '');
    $result &= Configuration::updateValue('ST_SELLERS_BOTTOM_PADDING', '');
    $result &= Configuration::updateValue('ST_SELLERS_TOP_MARGIN', '');
    $result &= Configuration::updateValue('ST_SELLERS_BOTTOM_MARGIN', '');
    $result &= Configuration::updateValue('ST_SELLERS_BG_PATTERN', 0);
    $result &= Configuration::updateValue('ST_SELLERS_BG_IMG', '');
    $result &= Configuration::updateValue('ST_SELLERS_BG_COLOR', '');
    $result &= Configuration::updateValue('ST_SELLERS_SPEED', 0);
    $result &= Configuration::updateValue('ST_SELLERS_TITLE_COLOR', '');
    $result &= Configuration::updateValue('ST_SELLERS_TITLE_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_SELLERS_TEXT_COLOR', '');
    $result &= Configuration::updateValue('ST_SELLERS_PRICE_COLOR', '');
    $result &= Configuration::updateValue('ST_SELLERS_LINK_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_SELLERS_GRID_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_SELLERS_DIRECTION_COLOR', '');
    $result &= Configuration::updateValue('ST_SELLERS_DIRECTION_BG', '');
    $result &= Configuration::updateValue('ST_SELLERS_DIRECTION_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_SELLERS_DIRECTION_DISABLED_BG', '');

    $result &= Configuration::updateValue('ST_SELLERS_TITLE_ALIGNMENT', 0);
    $result &= Configuration::updateValue('ST_SELLERS_TITLE_FONT_SIZE', 0);
    $result &= Configuration::updateValue('ST_SELLERS_DIRECTION_NAV', 0);

	return $result;
}
