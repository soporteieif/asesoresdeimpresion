<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_1_8($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayFullWidthTop2');
    
    return $result;
}
