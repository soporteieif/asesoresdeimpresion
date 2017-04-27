<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_5_9($object)
{
    $result = true;
    $result &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'st_product_categories_slider` MODIFY `display_on` int(10) unsigned NOT NULL DEFAULT 1');
        
    $hooks = array(
        array(
			'id' => 'displayFullWidthTop',
			'val' => '4',
			'flag' => 1
		),
		array(
			'id' => 'displayHomeTop',
			'val' => '2',
			'flag' => 1
		),
        array(
			'id' => 'displayHome',
			'val' => '1',
			'flag' => 1
		),
		array(
			'id' => 'displayHomeSecondaryLeft',
			'val' => '8',
			'flag' => 1
		),
		array(
			'id' => 'displayHomeSecondaryRight',
			'val' => '16',
			'flag' => 1
		),
		array(
			'id' => 'displayHomeTertiaryLeft',
			'val' => '32',
			'flag' => 1
		),
		array(
			'id' => 'displayHomeTertiaryRight',
			'val' => '64',
			'flag' => 1
		),
        array(
			'id' => 'displayHomeBottom',
			'val' => '128',
			'flag' => 1
		),
        array(
			'id' => 'displayBottomColumn',
			'val' => '256',
			'flag' => 1
		),
		array(
			'id' => 'displayHomeVeryBottom',
			'val' => '512',
			'flag' => 1
		),
        array(
			'id' => 'displayProductSecondaryColumn',
			'val' => '1024',
			'flag' => 1
		),
        array(
			'id' => 'displayLeftColumn',
			'val' => '2048',
			'flag' => 2
		),
		array(
			'id' => 'displayRightColumn',
			'val' => '4096',
			'flag' => 2
		),
		array(
			'id' => 'displayFooterTop',
			'val' => '8192',
			'flag' => 4
		),
        array(
			'id' => 'displayFooter',
			'val' => '16384',
			'flag' => 4
		),
        array(
			'id' => 'displayFooterSecondary',
			'val' => '32768',
			'flag' => 4
		)
    );
    
    if($rows = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'st_product_categories_slider` WHERE `id_shop`='.(int)Shop::getContextShopID()))
    {
        $hook_array = array();
        $current_hooks = Db::getInstance()->executeS('
        SELECT h.id_hook, h.name AS h_name
		FROM `'._DB_PREFIX_.'hook` h
		INNER JOIN `'._DB_PREFIX_.'hook_module` hm ON (h.id_hook = hm.id_hook)
		INNER JOIN `'._DB_PREFIX_.'module` AS m ON (m.id_module = hm.id_module)
		WHERE hm.id_module='.(int)$object->id.'
        AND hm.id_shop = '.(int)Shop::getContextShopID().'
		GROUP BY h.id_hook
        ');
        
        foreach($current_hooks as $hook)
            $hook_array[] = $hook['h_name'];
        foreach($hooks AS $key => $value)
            if(!in_array($value['id'], $hook_array))
                unset($hooks[$key]);
        foreach($rows AS $row)
        {
            $display_on = 0;
            foreach($hooks AS $v)
                if ($v['flag']&$row['display_on'])
                    $display_on += (int)$v['val'];
            if ($display_on)
                $result &= Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'st_product_categories_slider` SET `display_on`='.(int)$display_on.'
                WHERE `id_st_product_categories_slider`='.(int)$row['id_st_product_categories_slider']);
        }
    }
    $result &= $object->installExtraHook();
    
    return $result;
}
