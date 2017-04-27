<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_5($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayHomeVeryBottom');
    $result &= $object->registerHook('displayHomeTertiaryLeft');
    $result &= $object->registerHook('displayHomeTertiaryRight');
    $result &= $object->registerHook('displayMaintenance');
    
	return $result;
}
