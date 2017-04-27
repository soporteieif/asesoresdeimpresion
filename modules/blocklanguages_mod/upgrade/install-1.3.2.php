<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_2($object)
{
    $result = true;
    
    $result &= Configuration::updateValue('ST_LANGUAGES_FLAGS', 1);
    
	return $result;
}
