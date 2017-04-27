<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_1($object)
{
    $result = true;

    $result &= Configuration::updateGlobalValue('STSN_USE_VIEW_MORE_INSTEAD', 0);

	return $result;
}
