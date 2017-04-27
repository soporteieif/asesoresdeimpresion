<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_6_5($object)
{
    $result = true;
        
	$result &= Configuration::updateValue('STSN_BLOG_R_PRO_PER_LG_0', Configuration::get('ST_BLOG_RELATED_ITEMS'));
	$result &= Configuration::updateValue('STSN_BLOG_R_PRO_PER_MD_0', Configuration::get('ST_BLOG_RELATED_ITEMS'));
	$result &= Configuration::updateValue('STSN_BLOG_R_PRO_PER_SM_0', 3);
	$result &= Configuration::updateValue('STSN_BLOG_R_PRO_PER_XS_0', 2);
	$result &= Configuration::updateValue('STSN_BLOG_R_PRO_PER_XXS_0', 1);
    
    // delete tpl files
    $file_to_delete = array(
        'list/list_content.tpl',
        'list/form_category.tpl',
        'form/form_category.tpl',
    );
    foreach($file_to_delete AS $file)
        @unlink(_PS_MODULE_DIR_.$object->name.'/views/templates/admin/st_blog/helpers/'.$file);
            
	return $result;
}
