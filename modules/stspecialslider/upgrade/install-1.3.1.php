<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_1($object)
{
    $result = true;
	$result &= Configuration::updateValue('ST_SPECIAL_S_NBR_COL', 8) 
            && Configuration::updateValue('ST_SPECIAL_S_EASING_COL', 0)
            && Configuration::updateValue('ST_SPECIAL_S_SLIDESHOW_COL', 0)
            && Configuration::updateValue('ST_SPECIAL_S_S_SPEED_COL', 7000)
            && Configuration::updateValue('ST_SPECIAL_S_A_SPEED_COL', 400)
            && Configuration::updateValue('ST_SPECIAL_S_PAUSE_ON_HOVER_COL', 1)
            && Configuration::updateValue('ST_SPECIAL_S_LOOP_COL', 0)
            && Configuration::updateValue('ST_SPECIAL_S_MOVE_COL', 0)
            && Configuration::updateValue('ST_SPECIAL_S_ITEMS_COL', 4);
        
    $result &= $object->registerHook('addproduct') 
    && $object->registerHook('updateproduct') 
    && $object->registerHook('deleteproduct');
	return $result;
}
