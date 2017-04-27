<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_3_7($object)
{
	$result = true;
    
    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.'st_banner_group` `id_manufacturer`');  
      
    if(!is_array($field) || !count($field))
        if (!Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_banner_group` ADD `id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0'))
    		$result &= false;
            
    $_hooks = array(
        array('displayManufacturerHeader','displayManufacturerHeader','Display some specific informations on the manufacturer page',1),
    );
    foreach($_hooks as $v)
    {
        if(!$result)
            break;
            
        $id_hook = Hook::getIdByName($v[0]);
		if (!$id_hook)
		{
			$new_hook = new Hook();
			$new_hook->name = pSQL($v[0]);
			$new_hook->title = pSQL($v[1]);
			$new_hook->description = pSQL($v[2]);
			$new_hook->position = pSQL($v[3]);
			$new_hook->live_edit  = 0;
			$new_hook->add();
			$id_hook = $new_hook->id;
			if (!$id_hook)
				$result &= false;
		}
        else
        {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'hook` set `title`="'.$v[1].'", `description`="'.$v[2].'", `position`="'.$v[3].'" where `id_hook`='.$id_hook);
        }
    }
    
    if($result)
        $result &= $object->registerHook('displayManufacturerHeader');
        
    return $result;
}
