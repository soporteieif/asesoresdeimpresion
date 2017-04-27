<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_9($object)
{
    $result = true;
    $field_prefix = 'ST_B_FEATURED_A_';
    
	$result &= Configuration::updateValue($field_prefix.'CAT_MOD', 1);
    $result &= Configuration::updateValue($field_prefix.'CAT_MOD_H', 1);

	return $result;
}
