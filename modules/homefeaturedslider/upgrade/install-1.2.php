<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2($object)
{
	$result = true;
	$result &= Configuration::updateValue('HOME_FEATURED_S_NBR_MOD_COL', 8) 
            && Configuration::updateValue('HOME_FEATURED_S_EASING_COL', 0)
            && Configuration::updateValue('HOME_FEATURED_S_SLIDESHOW_COL', 0)
            && Configuration::updateValue('HOME_FEATURED_S_S_SPEED_COL', 7000)
            && Configuration::updateValue('HOME_FEATURED_S_A_SPEED_COL', 400)
            && Configuration::updateValue('HOME_FEATURED_S_PAUSE_COL', 1)
            && Configuration::updateValue('HOME_FEATURED_S_LOOP_COL', 0)
            && Configuration::updateValue('HOME_FEATURED_S_MOVE_COL', 0)
            && Configuration::updateValue('HOME_FEATURED_S_ITEMS_COL', 4)
            && Configuration::updateValue('HOME_FEATURED_S_SOBY_COL', 6);
        
    $result &= $object->registerHook('addproduct') 
    && $object->registerHook('updateproduct') 
    && $object->registerHook('deleteproduct');
	return $result;
}
