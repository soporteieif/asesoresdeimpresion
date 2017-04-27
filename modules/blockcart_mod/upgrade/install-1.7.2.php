<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_7_2($object)
{
    $result = true;
    
	$object->registerHook('displaySideBar');
        
    return $result;
}
