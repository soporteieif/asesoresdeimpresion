<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_1($object)
{
    $result = true;
	$result &= Configuration::updateValue('ST_HOMENEW_NBR_COL', 8) 
            && Configuration::updateValue('ST_HOMENEW_EASING_COL', 0)
            && Configuration::updateValue('ST_HOMENEW_SLIDESHOW_COL', 0)
            && Configuration::updateValue('ST_HOMENEW_S_SPEED_COL', 7000)
            && Configuration::updateValue('ST_HOMENEW_A_SPEED_COL', 400)
            && Configuration::updateValue('ST_HOMENEW_PAUSE_ON_HOVER_COL', 1)
            && Configuration::updateValue('ST_HOMENEW_LOOP_COL', 0)
            && Configuration::updateValue('ST_HOMENEW_MOVE_COL', 0)
            && Configuration::updateValue('ST_HOMENEW_ITEMS_COL', 1);
	 
    $result &= $object->registerHook('addproduct') 
    && $object->registerHook('updateproduct') 
    && $object->registerHook('deleteproduct');
	return $result;
}
