<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_4($object)
{
    return $object->registerHook('header');
}
