<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_8_2($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayFullWidthTop2');

	return $result;
}
