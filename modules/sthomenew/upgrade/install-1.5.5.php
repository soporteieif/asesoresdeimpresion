<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_5_5($object)
{
    $result = true;
	$result &= Configuration::updateValue('ST_HOMENEW_SOBY', 1);
	return $result;
}
