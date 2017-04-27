<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_4_9($object)
{
    $result = true;
	$result &= Configuration::updateValue('ST_SPECIAL_SOBY', 7);
	return $result;
}
