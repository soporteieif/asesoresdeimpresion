<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_5_3($object)
{
    $result = true;
 
    $result &= $object->registerHook('displayFullWidthTop');
    $result &= $object->registerHook('displayTopColumn');
    $result &= $object->registerHook('displayBottomColumn');
    
    return $result;
}
