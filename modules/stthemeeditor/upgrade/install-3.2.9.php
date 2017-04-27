<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_9($object)
{
    $result = true;

    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_VER_SUB_STYLE', 0);
    $result &= Configuration::updateGlobalValue('STSN_PRO_QUANTITY_INPUT', 0);
    
    $_hooks = array(
        array('displayComingSoon','displayComingSoon','Coming soon page',1),
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
    
	return $result;
}
