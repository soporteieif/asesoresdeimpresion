<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_0($object)
{
    $result = true;

    $result &= Configuration::updateGlobalValue('STSN_PRO_SHADOW_EFFECT', 1);
    $result &= Configuration::updateGlobalValue('STSN_PRO_H_SHADOW', 0);
    $result &= Configuration::updateGlobalValue('STSN_PRO_V_SHADOW', 0);
    $result &= Configuration::updateGlobalValue('STSN_PRO_SHADOW_BLUR', 4);
    $result &= Configuration::updateGlobalValue('STSN_PRO_SHADOW_COLOR', '#000000');
    $result &= Configuration::updateGlobalValue('STSN_PRO_SHADOW_OPACITY', 0.1);
    $result &= Configuration::updateGlobalValue('STSN_PRO_LIST_DISPLAY_BRAND_NAME', 0);
    $result &= Configuration::updateGlobalValue('STSN_PRODUCT_TABS', 0);
    $result &= Configuration::updateGlobalValue('STSN_MENU_TITLE', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_TITLE', 0);
    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_ADD_TO_CART', 0);
    $result &= Configuration::updateGlobalValue('STSN_FLYOUT_WISHLIST', 0);
    $result &= Configuration::updateGlobalValue('STSN_FLYOUT_QUICKVIEW', 0);
    $result &= Configuration::updateGlobalValue('STSN_FLYOUT_COMPARISON', 0);
    $logo_width = 4;
    if(Configuration::get('STSN_LOGO_POSITION'))
        $logo_width = 6;
    $result &= Configuration::updateGlobalValue('STSN_LOGO_WIDTH', $logo_width);
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_TOP_BORDER', 0);
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_BORDER', 0);
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_SECONDARY_BORDER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_SECONDARY_BORDER', 0);
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_INFO_BORDER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_INFO_BORDER', 0);
    $result &= Configuration::updateGlobalValue('STSN_HEADER_TOPBAR_OPACITY', 1);
    $result &= Configuration::updateGlobalValue('STSN_STICKY_MENU_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_STICKY_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_TOPBAR_BORDER', 0);
    $result &= Configuration::updateGlobalValue('STSN_TOPBAR_BORDER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_SECOND_FOOTER_LINK_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_F_TOP_BG_FIXED', 0);
    $result &= Configuration::updateGlobalValue('STSN_FOOTER_BG_FIXED', 0);
    $result &= Configuration::updateGlobalValue('STSN_F_SECONDARY_BG_FIXED', 0);
    $result &= Configuration::updateGlobalValue('STSN_F_INFO_BG_FIXED', 0);
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_TITLE_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_TITLE_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_FONT', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_FONT_SIZE', 0);
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_FONT_TRANS', 0);
    $result &= Configuration::updateGlobalValue('STSN_PRO_GRID_HOVER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_HEADER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_HEADER_LINK_HOVER', '');
        
    //Fix wrong initializations in 3.1.0
    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_PRO_CONDITION', 1);
    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_PRO_REFERENCE', 1);

    $_hooks = array(
        array('displayFooterBottomRight','displayFooterBottomRight','Footer bottom right',1),
        array('displayFooterBottomLeft','displayFooterBottomLeft','Footer bottom left',1),
        array('displayMobileBar','displayMobileBar','Mobile bar',1),
        array('displayMobileMenu','displayMobileMenu','Mobile menu',1),
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
    
    Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'image_type` SET `categories`=1 where `name`="home_default" OR `name`="home_default_2x"');

    //
    Tools::clearSmartyCache();
    Media::clearCache();

	return $result;
}
