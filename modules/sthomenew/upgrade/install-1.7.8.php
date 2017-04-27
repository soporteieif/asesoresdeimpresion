<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_8($object)
{
    $result = true;

    $result &= $object->registerHook('displayHeader');

    $result &= Configuration::updateValue('ST_HOMENEW_TOP_PADDING', '');
    $result &= Configuration::updateValue('ST_HOMENEW_BOTTOM_PADDING', '');
    $result &= Configuration::updateValue('ST_HOMENEW_TOP_MARGIN', '');
    $result &= Configuration::updateValue('ST_HOMENEW_BOTTOM_MARGIN', '');
    $result &= Configuration::updateValue('ST_HOMENEW_BG_PATTERN', 0);
    $result &= Configuration::updateValue('ST_HOMENEW_BG_IMG', '');
    $result &= Configuration::updateValue('ST_HOMENEW_BG_COLOR', '');
    $result &= Configuration::updateValue('ST_HOMENEW_SPEED', 0);
    $result &= Configuration::updateValue('ST_HOMENEW_TITLE_COLOR', '');
    $result &= Configuration::updateValue('ST_HOMENEW_TITLE_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_HOMENEW_TEXT_COLOR', '');
    $result &= Configuration::updateValue('ST_HOMENEW_PRICE_COLOR', '');
    $result &= Configuration::updateValue('ST_HOMENEW_LINK_HOVER_COLOR', '');
    $result &= Configuration::updateValue('ST_HOMENEW_GRID_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_HOMENEW_DIRECTION_COLOR', '');
    $result &= Configuration::updateValue('ST_HOMENEW_DIRECTION_BG', '');
    $result &= Configuration::updateValue('ST_HOMENEW_DIRECTION_HOVER_BG', '');
    $result &= Configuration::updateValue('ST_HOMENEW_DIRECTION_DISABLED_BG', '');

    $result &= Configuration::updateValue('ST_HOMENEW_TITLE_ALIGNMENT', 0);
    $result &= Configuration::updateValue('ST_HOMENEW_TITLE_FONT_SIZE', 0);
    $result &= Configuration::updateValue('ST_HOMENEW_DIRECTION_NAV', 0);

	return $result;
}
