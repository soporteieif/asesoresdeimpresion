<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_0($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_LG_3', 3);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_MD_3', 3);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_SM_3', 2);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_XS_3', 2);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_XXS_3', 1);

    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_LG_2', 4);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_MD_2', 4);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_SM_2', 3);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_XS_2', 2);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_XXS_2', 1);
    
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_LG_1', 5);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_MD_1', 5);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_SM_1', 4);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_XS_1', 3);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_PRO_PER_XXS_1', 2);

    $result &= Configuration::updateGlobalValue('STSN_HOMETAB_PRO_PER_LG_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_HOMETAB_PRO_PER_MD_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_HOMETAB_PRO_PER_SM_0', 3);
    $result &= Configuration::updateGlobalValue('STSN_HOMETAB_PRO_PER_XS_0', 2);
    $result &= Configuration::updateGlobalValue('STSN_HOMETAB_PRO_PER_XXS_0', 1);

    $result &= Configuration::updateGlobalValue('STSN_PACKITEMS_PRO_PER_LG_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_PACKITEMS_PRO_PER_MD_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_PACKITEMS_PRO_PER_SM_0', 3);
    $result &= Configuration::updateGlobalValue('STSN_PACKITEMS_PRO_PER_XS_0', 2);
    $result &= Configuration::updateGlobalValue('STSN_PACKITEMS_PRO_PER_XXS_0', 1);
    
    $result &= Configuration::updateGlobalValue('STSN_CATEGORY_SHOW_ALL_BTN', 0);

    $result &= Configuration::updateGlobalValue('STSN_CATEGORIES_PER_LG_0', 5);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORIES_PER_MD_0', 5);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORIES_PER_SM_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORIES_PER_XS_0', 3);
    $result &= Configuration::updateGlobalValue('STSN_CATEGORIES_PER_XXS_0', 2);
    
    $result &= Configuration::updateGlobalValue('STSN_CS_PER_LG_0', (int)Configuration::get('STSN_CS_ITEMS'));
    $result &= Configuration::updateGlobalValue('STSN_CS_PER_MD_0', (int)Configuration::get('STSN_CS_ITEMS'));
    $result &= Configuration::updateGlobalValue('STSN_CS_PER_SM_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_CS_PER_XS_0', 3);
    $result &= Configuration::updateGlobalValue('STSN_CS_PER_XXS_0', 2);

    $result &= Configuration::updateGlobalValue('STSN_PC_PER_LG_0', (int)Configuration::get('STSN_PC_ITEMS'));
    $result &= Configuration::updateGlobalValue('STSN_PC_PER_MD_0', (int)Configuration::get('STSN_PC_ITEMS'));
    $result &= Configuration::updateGlobalValue('STSN_PC_PER_SM_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_PC_PER_XS_0', 3);
    $result &= Configuration::updateGlobalValue('STSN_PC_PER_XXS_0', 2);
    
    $result &= Configuration::updateGlobalValue('STSN_AC_PER_LG_0', (int)Configuration::get('STSN_AC_ITEMS'));
    $result &= Configuration::updateGlobalValue('STSN_AC_PER_MD_0', (int)Configuration::get('STSN_AC_ITEMS'));
    $result &= Configuration::updateGlobalValue('STSN_AC_PER_SM_0', 4);
    $result &= Configuration::updateGlobalValue('STSN_AC_PER_XS_0', 3);
    $result &= Configuration::updateGlobalValue('STSN_AC_PER_XXS_0', 2);

    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_COLOR_LIST', 0);
    $result &= Configuration::updateGlobalValue('STSN_BREADCRUMB_WIDTH', 0);
    $result &= Configuration::updateGlobalValue('STSN_BREADCRUMB_BG_STYLE', 0);
    $result &= Configuration::updateGlobalValue('STSN_MEGAMENU_WIDTH', 0);
    $result &= Configuration::updateGlobalValue('STSN_FLYOUT_BUTTONS_BG', '');
    $result &= Configuration::updateGlobalValue('STSN_PRODUCT_BIG_IMAGE', 0);

    $_hooks = array(
        array('displayHomeVeryBottom','displayHomeVeryBottom','Very bottom of the home page',1),
        array('displayHomeTertiaryRight','displayHomeTertiaryRight','Home tertiary right',1),
        array('displayHomeTertiaryLeft','displayHomeTertiaryLeft','Home tertiary left',1),
        array('displayNav','displayNav','Header nav',1),
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
