<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_8($object)
{
    $result = true;
        
    $result &= $object->registerHook('displayFooterProduct') 
    && $object->registerHook('displayCategoryFooter');
    
    return $result;
}
