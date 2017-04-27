<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_7($object)
{
    $result = true;
    $result &= $object->registerHook('displayHeader');
        
    return $result;
}
