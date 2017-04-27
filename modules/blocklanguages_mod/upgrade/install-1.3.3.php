<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_3($object)
{
    $result = true;
    
	$result &= $object->registerHook('displaySideBar');
    
	return $result;
}
