<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_0($object)
{
    $result = true;
    $dest = _PS_OVERRIDE_DIR_.'/controllers/admin';
    if (!file_exists($dest))
    {
        @mkdir($dest, 0755, true);
        @chmod($dest, 0755);
    }
         
    if (is_writable($dest) && !file_exists($dest.'/AdminProductsController.php'))
    {
        @copy(_PS_MODULE_DIR_.'/stoverride/override/controllers/admin/AdminProductsController.php', $dest.'/AdminProductsController.php');
        @unlink(_PS_CACHE_DIR_.'/class_index.php');
    }

	return $result;
}
