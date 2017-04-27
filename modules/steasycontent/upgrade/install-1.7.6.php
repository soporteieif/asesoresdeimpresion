<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_6($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayTop');

	return $result;
}
