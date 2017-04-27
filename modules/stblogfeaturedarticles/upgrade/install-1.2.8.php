<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_8($object)
{
    $result = true;
    $field_prefix = 'ST_B_FEATURED_A_';
    
	$result &= Configuration::updateValue($field_prefix.'SOBY', 1);
    $result &= Configuration::updateValue($field_prefix.'GRID', 0);
    $result &= Configuration::updateValue($field_prefix.'HIDE_MOB', 0);
    $result &= Configuration::updateValue($field_prefix.'AW_DISPLAY', 1);
    
    $result &= Configuration::updateValue($field_prefix.'NBR_H', 6);
    $result &= Configuration::updateValue($field_prefix.'SOBY_H', 1);
    $result &= Configuration::updateValue($field_prefix.'GRID_H', 0);
    $result &= Configuration::updateValue($field_prefix.'HIDE_MOB_H', 0);
    $result &= Configuration::updateValue($field_prefix.'AW_DISPLAY_H', 1);
    
    
    $result &= Configuration::updateValue($field_prefix.'EASING', 0);
    $result &= Configuration::updateValue($field_prefix.'SLIDESHOW', 0);
    $result &= Configuration::updateValue($field_prefix.'S_SPEED', 7000);
    $result &= Configuration::updateValue($field_prefix.'A_SPEED', 400);
    $result &= Configuration::updateValue($field_prefix.'PAUSE_ON_HOVER', 1);
    $result &= Configuration::updateValue($field_prefix.'LOOP', 0);
    $result &= Configuration::updateValue($field_prefix.'MOVE', 0);
    
    $result &= Configuration::updateValue('STSN_BHOME_FB_PRO_PER_LG_0', 4);
    $result &= Configuration::updateValue('STSN_BHOME_FB_PRO_PER_MD_0', 4);
    $result &= Configuration::updateValue('STSN_BHOME_FB_PRO_PER_SM_0', 3);
    $result &= Configuration::updateValue('STSN_BHOME_FB_PRO_PER_XS_0', 2);
    $result &= Configuration::updateValue('STSN_BHOME_FB_PRO_PER_XXS_0', 1);
    $result &= Configuration::updateValue('STSN_HOME_FB_PRO_PER_LG_0', 4);
    $result &= Configuration::updateValue('STSN_HOME_FB_PRO_PER_MD_0', 4);
    $result &= Configuration::updateValue('STSN_HOME_FB_PRO_PER_SM_0', 3);
    $result &= Configuration::updateValue('STSN_HOME_FB_PRO_PER_XS_0', 2);
    $result &= Configuration::updateValue('STSN_HOME_FB_PRO_PER_XXS_0', 1);

    $result &= Configuration::updateValue($field_prefix.'TOP_PADDING', '');
    $result &= Configuration::updateValue($field_prefix.'BOTTOM_PADDING', '');
    $result &= Configuration::updateValue($field_prefix.'TOP_MARGIN', '');
    $result &= Configuration::updateValue($field_prefix.'BOTTOM_MARGIN', '');
    $result &= Configuration::updateValue($field_prefix.'BG_PATTERN', 0);
    $result &= Configuration::updateValue($field_prefix.'BG_IMG', '');
    $result &= Configuration::updateValue($field_prefix.'BG_COLOR', '');
    $result &= Configuration::updateValue($field_prefix.'SPEED', 0);
    $result &= Configuration::updateValue($field_prefix.'TITLE_COLOR', '');
    $result &= Configuration::updateValue($field_prefix.'TEXT_COLOR', '');
    $result &= Configuration::updateValue($field_prefix.'LINK_HOVER_COLOR', '');
    $result &= Configuration::updateValue($field_prefix.'DIRECTION_COLOR', '');
    $result &= Configuration::updateValue($field_prefix.'DIRECTION_BG', '');
    $result &= Configuration::updateValue($field_prefix.'DIRECTION_HOVER_BG', '');
    $result &= Configuration::updateValue($field_prefix.'DIRECTION_DISABLED_BG', '');

    $result &= Configuration::updateValue($field_prefix.'TITLE_ALIGNMENT', 0);
    $result &= Configuration::updateValue($field_prefix.'TITLE_FONT_SIZE', 0);
    $result &= Configuration::updateValue($field_prefix.'DIRECTION_NAV', 0);

	return $result;
}
