<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1_9($object)
{
	$result = true;
    
    $blank_rows = Db::getInstance()->executeS('
    SELECT `id_st_easy_tabs`
    FROM `'._DB_PREFIX_.'st_easy_tabs`
    WHERE `id_category` = 0
    AND `id_product` = 0
    AND `id_product_specific` = 0
    AND `allitems` = 0
    AND `id_manufacturer` = 0
    ');
    
    foreach($blank_rows AS $row)
    {
        $object = new StEasyTabsClass((int)$row['id_st_easy_tabs']);
        if ($object->id)
            $result &= $object->delete();
    }  
    
    return $result;
}
