<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_2($object)
{
    $result = true;

    $result &= Configuration::updateGlobalValue('STSN_BASE_BORDER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_STICKY_MOBILE_HEADER', 2);
    $result &= Configuration::updateGlobalValue('STSN_STICKY_MOBILE_HEADER_HEIGHT', 0);
    $result &= Configuration::updateGlobalValue('STSN_STICKY_MOBILE_HEADER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_STICKY_MOBILE_HEADER_BACKGROUND', '');
    $result &= Configuration::updateGlobalValue('STSN_STICKY_MOBILE_HEADER_BACKGROUND_OPACITY', 0.95);
    $result &= Configuration::updateGlobalValue('STSN_SIDE_BAR_BACKGROUND', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_STICKY_OPACITY', 0.95);
    $result &= Configuration::updateGlobalValue('STSN_CS_TITLE_NO_BG', 0);
    $result &= Configuration::updateGlobalValue('STSN_PC_TITLE_NO_BG', 0);
    $result &= Configuration::updateGlobalValue('STSN_AC_TITLE_NO_BG', 0);
    $result &= Configuration::updateGlobalValue('STSN_DIRECTION_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_DIRECTION_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_DIRECTION_HOVER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_DIRECTION_DISABLED_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_PAGINATION_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_PAGINATION_COLOR_HOVER', '');
    $result &= Configuration::updateGlobalValue('STSN_PAGINATION_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_PAGINATION_BG_HOVER'   , '');
    $result &= Configuration::updateGlobalValue('STSN_PAGINATION_BORDER', '');
    $result &= Configuration::updateGlobalValue('STSN_FORM_BG_COLOR', '');

    $_hooks = array(
        array('displaySideBar','displaySideBar','Side bar',1),
        array('displayFullWidthTop2','displayFullWidthTop2','Full width top 2',1),
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

    Db::getInstance()->execute('
        INSERT INTO `'._DB_PREFIX_.'image_type` (`name`, `width`, `height`, `products`, `categories`, `manufacturers`, `suppliers`, `scenes`)
        VALUES (\''.pSQL('thickbox_default_2x').'\', 700, 800, 1, 0, 0, 0, 1)');

    foreach(Shop::getCompleteListOfShopsID() AS $id_shop)
    {
        $cssFile = _PS_MODULE_DIR_ . $object->name . '/views/css/customer-s'.(int)$id_shop.'.css';
        @unlink($cssFile);    
    }
    
    $result &= $object->add_quick_access();
    $result &= $object->clear_class_index();
    //
    Tools::clearSmartyCache();
    Media::clearCache();
    
    // Rename cache folders to fix some bugs.
    $dir = _PS_ROOT_DIR_.'/cache/smarty/';
    @rename($dir.'cache', $dir.'cache_1610_del');
    @rename($dir.'compile', $dir.'compile_1610_del');
    
	return $result;
}
