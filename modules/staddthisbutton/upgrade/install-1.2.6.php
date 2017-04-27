<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2_6($object)
{
    $result = true;
    
    $result &= Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING', '');
    $result &= Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_S', '');
    $result &= Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_FOR_BLOG', '');
    $result &= Configuration::updateValue('ST_ADDTHIS_CUSTOMIZING_FBS', '');
    $result &= Configuration::updateValue('ST_ADDTHIS_SHOW_MORE', 1);
    $result &= Configuration::updateValue('ST_ADDTHIS_SHOW_MORE_FOR_BLOG', 1);
        
    return $result;
}
