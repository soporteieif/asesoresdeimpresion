<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_9($object)
{
	$result = true;
    
    $result &= Configuration::updateValue('STSN_BLOG_GRID_PER_LG_0', 3);
    $result &= Configuration::updateValue('STSN_BLOG_GRID_PER_MD_0', 2);
    $result &= Configuration::updateValue('STSN_BLOG_GRID_PER_SM_0', 2);
    $result &= Configuration::updateValue('STSN_BLOG_GRID_PER_XS_0', 2);
    $result &= Configuration::updateValue('STSN_BLOG_GRID_PER_XXS_0', 1);

    return $result;
}