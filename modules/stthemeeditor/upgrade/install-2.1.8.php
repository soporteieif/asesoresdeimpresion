<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_1_8($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_DISCOUNT_PERCENTAGE', 0);
    $result &= $object->registerHook('displayRightColumnProduct');
    
    $module_to_hook = array(
        'blockcart-actionCartListOverride',
        'blockmanufacturer-actionObjectManufacturerDeleteAfter',
        'blockmanufacturer-actionObjectManufacturerAddAfter',
        'blockmanufacturer-actionObjectManufacturerUpdateAfter',
        'blocksupplier-actionObjectSupplierUpdateAfter',
        'blocksupplier-actionObjectSupplierUpdateAfter',
        'blocksupplier-actionObjectSupplierUpdateAfter',
        'blockmyaccount-actionModuleRegisterHookAfter',
        'blockmyaccount-actionModuleUnRegisterHookAfter',
        'blockmyaccountfooter-actionModuleRegisterHookAfter',
        'blockmyaccountfooter-actionModuleUnRegisterHookAfter',
    );
    foreach($module_to_hook as $v)
    {
        $v_arr = explode('-',$v);
        $module_name= $v_arr[0];
        $hook_name = $v_arr[1];
        $id_module = Db::getInstance()->getValue('
    	SELECT `id_module` FROM `'._DB_PREFIX_.'module`
    	WHERE `name` = "'.$module_name.'"'
    	);
    
    	if ((int)$id_module > 0)
    	{
    		$id_hook = Db::getInstance()->getValue('
    		SELECT `id_hook` FROM `'._DB_PREFIX_.'hook` WHERE `name` = "'.$hook_name.'"
    		');
            if(!$id_hook)
            {
    			$new_hook = new Hook();
    			$new_hook->name = $hook_name;
    			$new_hook->title = $hook_name;
    			$new_hook->description = '';
    			$new_hook->live_edit  = 0;
    			$new_hook->add();
    			$id_hook = $new_hook->id;
            }
    		if ((int)$id_hook > 0)
    		{
    			$result &= Db::getInstance()->execute('
    			INSERT IGNORE INTO `'._DB_PREFIX_.'hook_module` (`id_module`, `id_hook`, `position`)
    			VALUES (
    			'.(int)$id_module.',
    			'.(int)$id_hook.',
    			(SELECT IFNULL(
    				(SELECT max_position from (SELECT MAX(position)+1 as max_position  FROM `'._DB_PREFIX_.'hook_module`  WHERE `id_hook` = '.(int)$id_hook.') AS max_position), 1))
    			)');
    		}
    	}
    }
    
	return $result;
}
