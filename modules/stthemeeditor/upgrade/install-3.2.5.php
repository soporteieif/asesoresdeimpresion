<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_5($object)
{
    $result = true;
    
    if(!Configuration::get('PS_DISPLAY_JQZOOM'))
    	$result &= Configuration::updateGlobalValue('STSN_ZOOM_TYPE', 2);

    $result &= Configuration::updateGlobalValue('STSN_BOXED_SHADOW_EFFECT', 1);
    $result &= Configuration::updateGlobalValue('STSN_BOXED_H_SHADOW', 0);
    $result &= Configuration::updateGlobalValue('STSN_BOXED_V_SHADOW', 0);
    $result &= Configuration::updateGlobalValue('STSN_BOXED_SHADOW_BLUR', 3);
    $result &= Configuration::updateGlobalValue('STSN_BOXED_SHADOW_COLOR', '#000000');
    $result &= Configuration::updateGlobalValue('STSN_BOXED_SHADOW_OPACITY', 0.1);

    $result &= Configuration::updateGlobalValue('STSN_SLIDE_LR_COLUMN', 1);
    $result &= Configuration::updateGlobalValue('STSN_PRO_THUMBNAILS', 0);
    $result &= Configuration::updateGlobalValue('STSN_CUSTOM_FONTS', '');

    $result &= Configuration::updateGlobalValue('STSN_SUBMEMUS_ANIMATION', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_SUBMEMUS_ANIMATION', 0);

    /*
    $pro_big_image = Configuration::get('STSN_PRODUCT_BIG_IMAGE');
    $pro_secondary = Configuration::get('STSN_PRODUCT_SECONDARY');
    $result &= Configuration::updateGlobalValue('STSN_PRO_IMAGE_COLUMN_MD', ($pro_big_image ? 6 : 4));
    $result &= Configuration::updateGlobalValue('STSN_PRO_PRIMARY_COLUMN_MD', ($pro_big_image ? 6 : ($pro_secondary ? 5: 8)));
    $result &= Configuration::updateGlobalValue('STSN_PRO_SECONDARY_COLUMN_MD', ($pro_big_image ? 0 : ($pro_secondary ? 3: 0)));
    $result &= Configuration::updateGlobalValue('STSN_PRO_IMAGE_COLUMN_SM', ($pro_big_image ? 6 : 4));
    $result &= Configuration::updateGlobalValue('STSN_PRO_PRIMARY_COLUMN_SM', ($pro_big_image ? 6 : ($pro_secondary ? 5: 8)));
    $result &= Configuration::updateGlobalValue('STSN_PRO_SECONDARY_COLUMN_SM', ($pro_big_image ? 0 : ($pro_secondary ? 3: 0)));
    */
    
	return $result;
}
