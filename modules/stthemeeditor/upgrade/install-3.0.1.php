<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_0_1($object)
{
    $result = true;
    
    $result &= Configuration::updateGlobalValue('STSN_MAIN_CON_BG_COLOR', '');
    $result &= Configuration::updateGlobalValue('STSN_DISPLAY_BANNER_BG', '');

    Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'theme` set `responsive`=1 WHERE directory = "transformer"');
    
	return $result;
}
