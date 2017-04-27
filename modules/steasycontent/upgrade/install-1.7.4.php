<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_4($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayFullWidthTop2');

	return $result;
}
