<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_1_0($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_OLD_PRICE_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_BODY_BG_COVER', 0);
    $result &= Configuration::updateGlobalValue('STSN_TOP_SPACING', 0);
    $result &= Configuration::updateGlobalValue('STSN_BOTTOM_SPACING', 0);
    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_PRO_CONDITION', 1);
    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_PRO_REFERENCE', 1);
    $result &= Configuration::updateGlobalValue('STSN_TOPBAR_HEIGHT', 0);
    $result &= Configuration::updateGlobalValue('STSN_MAIL_COLOR', '');

    $result &= Configuration::updateGlobalValue('STSN_ADV_MEGAMENU_POSITION', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_STICKY', 1);
    $result &= Configuration::updateGlobalValue('STSN_ADV_ST_MENU_HEIGHT', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_FONT_MENU', 'Fjalla One');
    $result &= Configuration::updateGlobalValue('STSN_ADV_FONT_MENU_SIZE', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_FONT_MENU_TRANS', 1);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MEGAMENU_WIDTH', 1);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_BG_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_BOTTOM_BORDER', 0);
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_BOTTOM_BORDER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_BOTTOM_BORDER_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_HOVER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_SECOND_MENU_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_SECOND_MENU_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_THIRD_MENU_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_THIRD_MENU_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_HOVER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_ITEMS1_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_ITEMS2_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_ITEMS3_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_ITEMS1_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_ITEMS2_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_ADV_MENU_MOB_ITEMS3_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_BG_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_HOVER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_HOVER_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_BORDER_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_C_MENU_BORDER_HOVER_COLOR', '');

    $result &= Configuration::updateGlobalValue('STSN_HEADER_BOTTOM_SPACING', 10);
    $result &= Configuration::updateGlobalValue('STSN_HEADER_PADDING', 0);
    
    if(Configuration::get('STSN_CART_ICON'))
        Configuration::updateGlobalValue('STSN_CART_ICON', 59452);
    if(Configuration::get('STSN_WISHLIST_ICON'))
        Configuration::updateGlobalValue('STSN_WISHLIST_ICON', 59392);
    if(Configuration::get('STSN_COMPARE_ICON'))
        Configuration::updateGlobalValue('STSN_COMPARE_ICON', 59400);

    $_hooks = array(
        array('displayFullWidthTop','displayFullWidthTop','Full width top',1),
        array('displayBottomColumn','displayBottomColumn','Bottom column',1),
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
        
	return $result;
}
