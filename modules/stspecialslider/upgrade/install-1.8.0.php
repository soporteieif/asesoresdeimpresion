<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_8_0($object)
{
    $result = true;
    $_prefix_st = 'ST_SPECIAL_';

    $result &= Configuration::updateGlobalValue($_prefix_st.'COUNTDOWN_ON', 1);
    $result &= Configuration::updateGlobalValue($_prefix_st.'COUNTDOWN_ON_COL', 1);
    
    $result &= $object->registerHook('displayAdminProductPriceFormFooter');
    
    $table = Db::getInstance()->executeS('SHOW TABLES LIKE "'._DB_PREFIX_.'st_special_product"');
    if (!count($table))
        $result &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_special_product` (
                 `id_product` int(10) NOT NULL,  
                 `id_shop` int(11) NOT NULL,                   
                PRIMARY KEY (`id_product`,`id_shop`),    
                KEY `id_shop` (`id_shop`)       
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

	return $result;
}
