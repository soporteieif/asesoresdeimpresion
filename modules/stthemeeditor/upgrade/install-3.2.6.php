<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_6($object)
{
    $result = true;

    $result &= Configuration::updateGlobalValue('STSN_MENU_ICON_WITH_TEXT', 0);
    $result &= Configuration::updateGlobalValue('STSN_PRO_IMG_HOVER_SCALE', 0);
    $result &= Configuration::updateGlobalValue('STSN_HEAD_CODE', '');
    $result &= Configuration::updateGlobalValue('STSN_PRO_SHOW_PRINT_BTN', 0);
    $result &= Configuration::updateGlobalValue('STSN_FONT_BODY_SIZE', 0);
    $result &= Configuration::updateGlobalValue('STSN_FONT_ARABIC_SUPPORT', 0);
    $result &= Configuration::updateGlobalValue('STSN_FONT_PRODUCT_NAME', '');
    $result &= Configuration::updateGlobalValue('STSN_FONT_PRODUCT_NAME_TRANS', 0);
    $result &= Configuration::updateGlobalValue('STSN_FONT_PRODUCT_NAME_SIZE', 0);
    $result &= Configuration::updateGlobalValue('STSN_FONT_PRODUCT_NAME_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_SECOND_FONT_MENU', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_THIRD_FONT_MENU', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_SECOND_FONT_MENU_SIZE', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_THIRD_FONT_MENU_SIZE', 0);

    $_hooks = array(
        array('displayMainMenuWidget','displayMainMenuWidget','Menu widgets',1),
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
    
    foreach(Shop::getCompleteListOfShopsID() AS $id_shop)
    {
        $cssFile = _PS_MODULE_DIR_ . $object->name . '/views/css/customer-s'.(int)$id_shop.'.css';
        @unlink($cssFile);    
    }
    
    $result &= $object->clear_class_index();
    
	return $result;
}
