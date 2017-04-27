<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_2($object)
{
    $result = true;
	$socials = array('facebook','twitter','rss','youtube','pinterest','google','wordpress','drupal','vimeo','flickr','digg','eaby','amazon','instagram','linkedin','blogger','tumblr','vkontakte','skype');
    $languages = Language::getLanguages(false);
    foreach($socials AS $social)
    {
        $value = Configuration::get('ST_SOCIAL_'.strtoupper($social));
        if (!$value)
           continue; 
        $data = array();
        foreach($languages AS $language)
            $data[$language['id_lang']] = $value;
        $result &= Configuration::updateValue('ST_SOCIAL_'.strtoupper($social), $data);
    }
        
	return $result;
}
