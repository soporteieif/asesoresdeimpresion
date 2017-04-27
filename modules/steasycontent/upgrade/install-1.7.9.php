<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_9($object)
{
    $result = true;
    
    $result &= $object->registerHook('displaypaymentReturn');
    $result &= $object->registerHook('displayComingSoon');

	return $result;
}
