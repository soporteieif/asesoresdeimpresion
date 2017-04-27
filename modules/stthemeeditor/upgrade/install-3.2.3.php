<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_3($object)
{
    $result = true;
    
    // Rename cache folders to fix some bugs.
    $dir = _PS_ROOT_DIR_.'/cache/smarty/';
    @rename($dir.'cache', $dir.'cache_1611_del');
    @rename($dir.'compile', $dir.'compile_1611_del');
    
	return $result;
}
