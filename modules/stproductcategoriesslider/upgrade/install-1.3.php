<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3($object)
{
	$result = true;
	$result &= Configuration::updateValue('ST_PRO_CATE_EASING_COL', 0)
            && Configuration::updateValue('ST_PRO_CATE_SLIDESHOW_COL', 0)
            && Configuration::updateValue('ST_PRO_CATE_S_SPEED_COL', 7000)
            && Configuration::updateValue('ST_PRO_CATE_A_SPEED_COL', 400)
            && Configuration::updateValue('ST_PRO_CATE_PAUSE_ON_HOVER_COL', 1)
            && Configuration::updateValue('ST_PRO_CATE_LOOP_COL', 0)
            && Configuration::updateValue('ST_PRO_CATE_MOVE_COL', 0)
            && Configuration::updateValue('ST_PRO_CATE_ITEMS_COL', 4);
        
    $result &= $object->registerHook('actionCategoryDelete')
    && $object->registerHook('addproduct') 
    && $object->registerHook('updateproduct') 
    && $object->registerHook('deleteproduct');
	return $result;
}
