<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_4($object)
{
    $result = true;
    
    $result &= $object->registerHook('displayHomeVeryBottom');

    $cssFile = _PS_MODULE_DIR_ . $object->name. '/views/css/custom.css';
    @unlink($cssFile);
            
    return $result;
}