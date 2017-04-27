<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_5_9($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayRightColumnProduct');
    $result &= $object->registerHook('displayLeftColumnProduct');
    $result &= $object->registerHook('displayProductButtons');
    $result &= $object->registerHook('displayBanner');
    
	return $result;
}
