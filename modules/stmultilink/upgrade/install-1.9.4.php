<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_9_4($object)
{
	$result = true;
	$result &= $object->registerHook('displayFooterBottomRight');
	$result &= $object->registerHook('displayFooterBottomLeft');
	$result &= $object->registerHook('displayTop');
	$result &= $object->registerHook('displayTopLeft');
    return $result;
}
