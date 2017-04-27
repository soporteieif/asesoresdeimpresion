<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_5_6($object)
{
    $result = true;
    
	$object->unregisterHook('displayMobileBar');
	$object->registerHook('displaySideBar');
        
    return $result;
}
