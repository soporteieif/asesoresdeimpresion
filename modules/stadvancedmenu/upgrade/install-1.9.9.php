<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_9_9($object)
{
    $result = true;
    
	$object->registerHook('displayMobileMenu');
        
    return $result;
}
