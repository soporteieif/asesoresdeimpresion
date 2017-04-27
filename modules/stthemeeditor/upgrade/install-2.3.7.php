<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_3_7($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_TRACKING_CODE', '');
    $result &= Configuration::updateGlobalValue('STSN_NEW_STYLE', 0);
    $result &= Configuration::updateGlobalValue('STSN_NEW_BG_IMG', '');
    $result &= Configuration::updateGlobalValue('STSN_NEW_STICKERS_TOP', 25);
    $result &= Configuration::updateGlobalValue('STSN_NEW_STICKERS_RIGHT', 0);
    $result &= Configuration::updateGlobalValue('STSN_SALE_STYLE', 0);
    $result &= Configuration::updateGlobalValue('STSN_SALE_BG_IMG', '');
    $result &= Configuration::updateGlobalValue('STSN_SALE_STICKERS_TOP', 25);
    $result &= Configuration::updateGlobalValue('STSN_SALE_STICKERS_LEFT', 0);
    $result &= Configuration::updateGlobalValue('STSN_PRICE_DROP_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_PRICE_DROP_BORDER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_PRICE_DROP_BG_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_PRICE_DROP_BOTTOM', 50);
    $result &= Configuration::updateGlobalValue('STSN_PRICE_DROP_RIGHT', 10);
    $result &= Configuration::updateGlobalValue('STSN_PRICE_DROP_WIDTH', 0);
    $result &= Configuration::updateGlobalValue('STSN_LOGO_POSITION', 0);
    $result &= Configuration::updateGlobalValue('STSN_LOGO_HEIGHT', 0);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORIES_PER_ROW', 5);
    
    $_hooks = array(
        array('displayTopLeft','displayTopLeft','Top left-hand side of the page',1),
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
