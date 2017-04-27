<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_0_9($object)
{
    $result = true;
    
	$object->registerHook('displaySideBar');
        
    return $result;
}
