<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_5_7($object)
{
    $result = true;
    if( Configuration::get('STSN_PRO_CATE_PRO_PER_LG_0') === false )
            $result &= Configuration::updateValue('ST_PRO_CATE_DISPLAY_SD', 0);
    if( Configuration::get('ST_PRO_CATE_GRID') === false )
            $result &= Configuration::updateValue('ST_PRO_CATE_GRID', 0);
            
    $result &= Configuration::updateValue('STSN_PRO_CATE_PRO_PER_LG_0', Configuration::get('STSN_PRO_CATE_PRO_PER_LG_0') ? Configuration::get('STSN_PRO_CATE_PRO_PER_LG_0') : Configuration::get('ST_PRO_CATE_ITEMS'));
    $result &= Configuration::updateValue('STSN_PRO_CATE_PRO_PER_MD_0', Configuration::get('STSN_PRO_CATE_PRO_PER_MD_0') ? Configuration::get('STSN_PRO_CATE_PRO_PER_MD_0') : Configuration::get('ST_PRO_CATE_ITEMS'));
    $result &= Configuration::updateValue('STSN_PRO_CATE_PRO_PER_SM_0', Configuration::get('STSN_PRO_CATE_PRO_PER_SM_0') ? Configuration::get('STSN_PRO_CATE_PRO_PER_SM_0') : 3);
    $result &= Configuration::updateValue('STSN_PRO_CATE_PRO_PER_XS_0', Configuration::get('STSN_PRO_CATE_PRO_PER_XS_0') ? Configuration::get('STSN_PRO_CATE_PRO_PER_XS_0') : 2);
    $result &= Configuration::updateValue('STSN_PRO_CATE_PRO_PER_XXS_0', Configuration::get('STSN_PRO_CATE_PRO_PER_XXS_0') ? Configuration::get('STSN_PRO_CATE_PRO_PER_XXS_0') : 1);
    return $result;
}
