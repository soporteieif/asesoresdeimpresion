<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_9($object)
{
    $result = $object->registerHook('displayTopLeft');
	return $result;
}
