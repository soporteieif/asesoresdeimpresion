<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_6($object)
{
    $result = true;
    $id_hook = Hook::getIdByName('displayTopSecondary');
    foreach(Shop::getShops(true, null, true) AS $id_shop)
    {
        $res = Db::getInstance()->getValue('
			SELECT count(0)
			FROM `'._DB_PREFIX_.'hook_module` hm
            WHERE hm.id_shop = '.(int)$id_shop.'
            AND hm.id_hook = '.(int)$id_hook.'
            AND hm.id_module = '.(int)$object->id);
        if ($res)
        {
            $result &= $object->unregisterHook('displayTopSecondary', array($id_shop));
        	$result &= $object->registerHook('displayMainMenuWidget', array($id_shop));   
        }
    }
    
	return $result;
}
