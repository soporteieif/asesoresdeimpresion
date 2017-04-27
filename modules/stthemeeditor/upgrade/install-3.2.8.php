<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_8($object)
{
    $result = true;

    $result &= Configuration::updateGlobalValue('STSN_USE_MOBILE_HEADER', 1);
    
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MULTI_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MULTI_BG_HOVER', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_OPEN', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_TITLE_WIDTH', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_TITLE_ALIGN', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_TITLE', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_HOVER_TITLE', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_HOVER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_ITEM_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_ITEM_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_ITEM_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_ITEM_HOVER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_VER_FONT_MENU_SIZE', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_VER_FONT_MENU', '');
    
    $result &= Configuration::updateGlobalValue('STSN_F_TOP_H_ALIGN', 0);
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_H_ALIGN', 0);
    $result &= Configuration::updateGlobalValue('STSN_F_SECONDARY_H_ALIGN', 0);
    $result &= Configuration::updateGlobalValue('STSN_TRANSPARENT_MOBILE_HEADER', 0);
    $result &= Configuration::updateGlobalValue('STSN_TRANSPARENT_MOBILE_HEADER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_TRANSPARENT_MOBILE_HEADER_OPACITY', 0.4);
    $result &= Configuration::updateGlobalValue('STSN_HEADER_TEXT_TRANS', 0);
    $result &= Configuration::updateGlobalValue('STSN_F_INFO_CENTER', 0);

	return $result;
}
