<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
include_once dirname(__FILE__).'/StIosSliderGroup.php';
include_once dirname(__FILE__).'/stiosslider.php';

$result = array(
    'r' => false,
    'm' => '',
    'd' => ''
);

if(Tools::getValue('action')=='delete_image' && $identi = Tools::getValue('identi'))
{
    $slide_group = new StIosSliderGroup((int)(int)$identi);
    if(Validate::isLoadedObject($slide_group))
    {
        $slide_group->bg_img = '';
        if($slide_group->save())
        {
            $module_instance = new StIosSlider();
            $module_instance->writeCss();
            $result['r'] = true;
        }
    }
    echo json_encode($result);
}
else
    echo json_encode($result);
    

