<?php
/*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_'))
	exit;
define('_ST_DEMO_DEBUG_', true);
class DemoStore
{
   private $_debug_file = 'store_data.dbg';
   public $hooks = array(
    //Top bar
    'displayBanner',
    'displayNav',
    // Header
    'displayTop','displayTopLeft',
    'displayTopSecondary','displayMainMenuWidget',
    // Main content top
    'displayFullWidthTop','displayFullWidthTop2',
    'displayTopColumn',
    // Home page
    'displayHomeTop',
    'displayHome',
    'displayHomeTab','displayHomeTabContent',
    'displayHomeSecondaryLeft','displayHomeSecondaryRight',
    'displayHomeTertiaryLeft','displayHomeTertiaryRight',
    'displayHomeBottom',
    // Main content buttom.
    'displayBottomColumn',
    'displayHomeVeryBottom',
    // Left/right column.
    'displayLeftColumn',//'displayRightColumn',
    // Footer
    'displayFooterTop','displayFooter','displayFooterSecondary','displayFooterBottomLeft','displayFooterBottomRight',
    );
   public $module_tables = array(
        // module name => array(table => array(reference table))
        // The fist table if main table.
        // Reference table must be behind on current table to be defined.
        'stadvancedbanner' => array(
            'st_advanced_banner_group' => array(),
            'st_advanced_banner' => array('st_advanced_banner_group'),
            'st_advanced_banner_lang' => array('st_advanced_banner'),
            'st_advanced_banner_group_shop' => array('st_advanced_banner_group'),
            'st_advanced_banner_font' => array('st_advanced_banner'),
        ),
        'stbanner' => array(
            'st_banner_group' => array(),
            'st_banner' => array('st_banner_group'),
            'st_banner_lang' => array('st_banner'),
            'st_banner_group_shop' => array('st_banner_group'),
        ),
        'steasycontent' => array(
            'st_easy_content' => array(),
            'st_easy_content_lang' => array('st_easy_content'),
            'st_easy_content_shop' => array('st_easy_content'),
            'st_easy_content_font' => array('st_easy_content'),
        ),
        'stiosslider' => array(
            'st_iosslider_group' => array(),
            'st_iosslider' => array('st_iosslider_group'),
            'st_iosslider_lang' => array('st_iosslider'),
            'st_iosslider_group_shop' => array('st_iosslider_group'),
            'st_iosslider_font' => array('st_iosslider'),
        ),
        'stmultilink' => array(
            'st_multi_link_group' => array(),
            'st_multi_link_group_lang' => array('st_multi_link_group'),
            'st_multi_link' => array('st_multi_link_group'),
            'st_multi_link_lang' => array('st_multi_link'),
            'st_multi_link_group_shop' => array('st_multi_link_group'),
        ),
        'stnewsletter' => array(
            'st_news_letter' => array(),
            'st_news_letter_lang' => array('st_news_letter'),
            'st_news_letter_shop' => array('st_news_letter'),
        ),
        'stowlcarousel' => array(
            'st_owl_carousel_group' => array(),
            'st_owl_carousel' => array('st_owl_carousel_group'),
            'st_owl_carousel_lang' => array('st_owl_carousel'),
            'st_owl_carousel_group_shop' => array('st_owl_carousel_group'),
            'st_owl_carousel_font' => array('st_owl_carousel'),
        ),
        'stparallax' => array(
            'st_parallax_group' => array(),
            'st_parallax_group_lang' => array('st_parallax_group'),
            'st_parallax' => array('st_parallax_group'),
            'st_parallax_lang' => array('st_parallax'),
            'st_parallax_group_shop' => array('st_parallax_group'),
            'st_parallax_font' => array('st_parallax'),
        ),
    );
    public $global = array(
        'sql' => array(),
        'config' => array(
            'STSN_',
            // Qucik search block mod
            'ST_QUICK_SEARCH_SIMPLE',
            // Cart block mod
            'ST_BLOCK_CART_STYLE',
            // Social networking block
            'ST_SOCIAL_COLOR','ST_SOCIAL_HOVER_COLOR','ST_SOCIAL_BG','ST_SOCIAL_HOVER_BG','ST_SOCIAL_WIDE_ON_FOOTER',
        )
    );
    public $data = array();
   
    public function __construct($data = array())
    {
        set_time_limit(600);
        $this->_debug_file = _PS_MODULE_DIR_.'stthemeeditor/config/store_data.dbg';
        if (_ST_DEMO_DEBUG_ && file_exists($this->_debug_file)) {
            @unlink($this->_debug_file);
        }
        if (is_array($data) && count($data)) {
            $this->data = $data;
        }
    }
    
    public function import_modules()
    {
        foreach($this->hooks AS $hook) {
            $id_hook = Hook::get($hook);
            $modules = (array)Hook::getModulesFromHook($id_hook);
            // Unregester modules in this hook.
            foreach($modules AS $module) {
                $inst = Module::getInstanceByName($module['name']);
                if (!is_object($inst) 
                || (strtoupper($inst->author) != 'SUNNYTOO.COM'
                && strtoupper($inst->author) != 'PRESTASHOP'
                && $inst->name != 'revsliderprestashop')
                || $inst->name == 'stthemeeditor') {
                    continue;
                }
                $inst->unregisterHook($id_hook, array((int)Context::getContext()->shop->id));
            }
            // Import new modules.
            $this->import_module_data($hook);
            // Clear cache.
            Cache::clean('hook_module_list');
        }
        // Import global
        $themeeditor = Module::getInstanceByName('stthemeeditor');
        $exclude = array();
        foreach($themeeditor->defaults AS $key => $value) {
            if (!$value['exp']) {
                $exclude[] = 'STSN_'.strtoupper($key);
            }
        }
        if (isset($this->data['global'])) {
            foreach($this->data['global'] AS $key => $value) {
                foreach($value AS $tbls => $v) {
                    if ($key == 'sql') {
                        $where_id_shop = '';
                        $s = array('{id_theme}');
                        $r = array(Context::getContext()->theme->id);
                        foreach(explode('|',$tbls) AS $table) {
                            $s[] = '{'.$table.'}';
                            $r[] = _DB_PREFIX_.$table;
                            $field = Db::getInstance()->executeS('DESC '._DB_PREFIX_.$table.' id_shop');
                            if (is_array($field) && count($field)) {
                                $where_id_shop = ' AND id_shop='.(int)Context::getContext()->shop->id;
                            }
                        }
                        $sql = str_replace($s, $r, $v).$where_id_shop;
                        if (_ST_DEMO_DEBUG_) {
                            @file_put_contents($this->_debug_file, $sql."\r\n", FILE_APPEND);
                        }
                        Db::getInstance()->execute($sql);
                    } elseif ($key == 'config') {
                        if (in_array($v['name'], $exclude)) {
                            continue;
                        }
                        Configuration::updateValue(strtoupper($v['name']), $v['value']);
                    }
                }
            }
        }
        $this->import_menu();
        // Clear cache files.
        if (file_exists(_PS_MODULE_DIR_.'stiosslider/views/css/custom.css')) {
            @unlink(_PS_MODULE_DIR_.'stiosslider/views/css/custom.css');    
        }
        if (_ST_DEMO_DEBUG_) {
            @file_put_contents($this->_debug_file, print_r($this->data, true), FILE_APPEND);
        }
    }
    
    private function import_module_data($hook)
    {
        if (!$hook) {
            return false;
        }
        $module_tables = $this->module_tables;
        $content = $this->data;
        if(isset($content[$hook]) && $content[$hook]) {
            foreach($content[$hook] AS $module => $data) {
                $inst = Module::getInstanceByName($module);
                if (is_object($inst)) {
                    $inst->registerHook($hook, array((int)Context::getContext()->shop->id));
                }
                if (!is_array($data)) {
                    $data = (array)$data;
                }
                if (isset($data['disabled']) && $data['disabled'] || (!isset($data['disabled']) && $inst->name != 'stthemeeditor')) {
                    if (Module::isEnabled($module)) {
                        is_object($inst) && $inst->disable();    
                    }
                    continue;
                } elseif(!Module::isEnabled($module)) {
                    is_object($inst) && $inst->enable();
                }
                if (isset($data['sql']) && count($data['sql'])) {
                    $db = Db::getInstance();
                    foreach($data['sql'] AS $table => $rows) {
                        // Deactivate old rows.
                        $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.$table.'` `active`');
                        if(is_array($field) && count($field)) {
                            if (key_exists($table.'_shop', $module_tables[$module])) {
                                $sql = '';
                                $sql = 'UPDATE '._DB_PREFIX_.$table.' SET `active` = 0';
                                $sql .= ' WHERE id_'.$table.' IN (SELECT id_'.$table.' FROM '._DB_PREFIX_.$table.'_shop WHERE id_shop='.(int)Context::getContext()->shop->id.')';
                                $db->execute($sql);
                                if (_ST_DEMO_DEBUG_) {
                                    @file_put_contents($this->_debug_file, $sql."\r\n", FILE_APPEND);
                                }
                            } else {
                                $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.$table.'` `id_shop`');
                                if(is_array($field) && count($field)) {
                                    $sql = '';
                                    $sql = 'UPDATE '._DB_PREFIX_.$table.' SET `active` = 0 WHERE `id_shop`='.(int)Context::getContext()->shop->id;
                                    $db->execute($sql);
                                    if (_ST_DEMO_DEBUG_) {
                                        @file_put_contents($this->_debug_file, $sql."\r\n", FILE_APPEND);
                                    }
                                }
                            }
                        }
                        if (count($rows)) {
                            //Assign modified data again.
                            $rows = $data['sql'][$table];
                            foreach($rows AS $_k => $row) {
                                $row = $data['sql'][$table][$_k];
                                // Set id_shop to context id_shop.
                                if (isset($row['id_shop'])) {
                                    $row['id_shop'] = Context::getContext()->shop->id;
                                }
                                if (isset($row['done'])) {
                                    unset($row['done']);
                                }
                                $row_id = 0;
                                if (strpos($table,'_lang') !== false) {
                                    foreach($row AS &$_v) {
                                        $_v = pSQL($_v, true);
                                    }
                                    foreach(Language::getLanguages(false) AS $lang) {
                                        $row['id_lang'] = $lang['id_lang'];
                                        $db->insert($table, $row, false, true, Db::INSERT_IGNORE);
                                    }
                                    if (_ST_DEMO_DEBUG_) {
                                        @file_put_contents($this->_debug_file, $table.'=====>table_lang'."\r\n".print_r($row, true), FILE_APPEND);
                                    }
                                }
                                else {
                                    //Remove primary key.
                                    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.$table.'` `id_'.$table.'`');
                                    if (is_array($field) && count($field) && $field[0]['Key'] == 'PRI') {
                                        $old_id = $row['id_'.$table];
                                        unset($row['id_'.$table]);
                                    } else {
                                        $old_id = $row['id_'.$module_tables[$module][$table][0]]; 
                                    }
                                    if($db->insert($table, $row, false, true, Db::INSERT_IGNORE)) {
                                       $row_id = $db->Insert_ID();
                                    }
                                    if (_ST_DEMO_DEBUG_) {
                                        @file_put_contents($this->_debug_file, $table.'=====>'.$row_id."\r\n".print_r($row, true), FILE_APPEND);
                                    }
                                   $data['sql'] = $this->process_reference($data['sql'], $row_id, $old_id, $table, $module);    
                                }
                            } 
                        }
                    }
                }
                if (isset($data['config']) && count($data['config'])) {
                    foreach($data['config'] AS $setting) {
                        $rs =  Configuration::updateValue(strtoupper($setting['name']), $setting['value']);
                    }
                }
                // Rev slider
                if ($hook == 'displayFullWidthTop' && $module == 'revsliderprestashop') {
                    $this->import_rev_slider($inst->version);
                }
            }
        }
        return true;
    }
    
    public function import_menu()
    {
        $db = Db::getInstance();
        $id_shop = (int)Context::getContext()->shop->id;
        $source_id_shop = isset($this->data['source_id_shop']) ? $this->data['source_id_shop'] : 0;
        // Without menu on the columns.
        $without_moc = in_array($source_id_shop, array(13));
        // Column menu.
        $field = $db->executeS('DESC '._DB_PREFIX_.'theme_meta id_shop');
        $left = $db->getValue('SELECT left_column FROM '._DB_PREFIX_.'theme_meta 
        WHERE id_meta=(SELECT id_meta FROM '._DB_PREFIX_.'meta WHERE page="index") 
        AND id_theme='.(int)Context::getContext()->theme->id.' 
        '.(is_array($field) && count($field) ? ' AND id_shop='.$id_shop : ''));
        if ($left && !$without_moc && !$db->getValue('SELECT COUNT(0) FROM '._DB_PREFIX_.'st_advanced_menu WHERE location=1 AND id_shop='.$id_shop)) {
            $db->execute("INSERT INTO `"._DB_PREFIX_."st_advanced_menu`(`location`,`id_st_advanced_column`,`id_parent`,
            `level_depth`,`id_shop`,`item_k`,`item_v`,`subtype`,`position`,`active`,`new_window`,`txt_color`,`link_color`,
            `bg_color`,`txt_color_over`,`bg_color_over`,`tab_content_bg`,`auto_sub`,`nofollow`,`hide_on_mobile`,`alignment`,
            `width`,`is_mega`,`sub_levels`,`sub_limit`,`item_limit`,`items_md`,`icon_class`,`item_t`,`cate_label_color`,
            `cate_label_bg`,`show_cate_img`,`bg_image`,`bg_repeat`,`bg_position`,`bg_margin_bottom`,`granditem`) 
            VALUES (1,0,0,0,".$id_shop.",0,'',0,1,1,0,'','','','','','',0,0,0,0,12.0,1,0,0,0,0,'',0,'','',0,'',0,0,0,0)");
            if ($indert_id = $db->Insert_ID()) {
                foreach(Language::getLanguages(false) AS $lang) {
                    $db->execute("INSERT INTO `"._DB_PREFIX_."st_advanced_menu_lang`(`id_st_advanced_menu`,`id_lang`,`title`,
                    `link`,`html`,`cate_label`) VALUES (".(int)$indert_id.",".(int)$lang['id_lang'].",'A sample menu','','','')");
                }
            }
        } elseif ($left && !$without_moc && $db->getValue('SELECT COUNT(0) FROM '._DB_PREFIX_.'st_advanced_menu WHERE location=1 AND active=0 AND id_shop='.$id_shop)) {
            $db->execute('UPDATE '._DB_PREFIX_.'st_advanced_menu SET active=1 WHERE location=1 AND id_shop='.$id_shop);
        } elseif ((!$left || $without_moc) && $db->getValue('SELECT COUNT(0) FROM '._DB_PREFIX_.'st_advanced_menu WHERE location=1 AND id_shop='.$id_shop)) {
            $db->execute('UPDATE '._DB_PREFIX_.'st_advanced_menu SET active=0 WHERE location=1 AND id_shop='.$id_shop);
        }
        // Vertical menu
        if (in_array($source_id_shop, array(6, 13)) && !$db->getValue('SELECT COUNT(0) FROM '._DB_PREFIX_.'st_advanced_menu WHERE location=2 AND id_shop='.$id_shop)) {
            $db->execute("INSERT INTO `"._DB_PREFIX_."st_advanced_menu`(`location`,`id_st_advanced_column`,`id_parent`,
            `level_depth`,`id_shop`,`item_k`,`item_v`,`subtype`,`position`,`active`,`new_window`,`txt_color`,`link_color`,
            `bg_color`,`txt_color_over`,`bg_color_over`,`tab_content_bg`,`auto_sub`,`nofollow`,`hide_on_mobile`,`alignment`,
            `width`,`is_mega`,`sub_levels`,`sub_limit`,`item_limit`,`items_md`,`icon_class`,`item_t`,`cate_label_color`,
            `cate_label_bg`,`show_cate_img`,`bg_image`,`bg_repeat`,`bg_position`,`bg_margin_bottom`,`granditem`) 
            VALUES (2,0,0,0,".$id_shop.",0,'',0,1,1,0,'','','','','','',0,0,0,0,12.0,1,0,0,0,0,'',0,'','',0,'',0,0,0,0)");
            if ($indert_id = $db->Insert_ID()) {
                foreach(Language::getLanguages(false) AS $lang) {
                    $db->execute("INSERT INTO `"._DB_PREFIX_."st_advanced_menu_lang`(`id_st_advanced_menu`,`id_lang`,`title`,
                    `link`,`html`,`cate_label`) VALUES (".(int)$indert_id.",".(int)$lang['id_lang'].",'A sample vertical menu','','','')");
                }
            }
        } elseif(in_array($source_id_shop, array(6, 13)) && $db->getValue('SELECT COUNT(0) FROM '._DB_PREFIX_.'st_advanced_menu WHERE location=2 AND active=0 AND id_shop='.$id_shop)) {
            $db->execute('UPDATE '._DB_PREFIX_.'st_advanced_menu SET active=1 WHERE location=2 AND id_shop='.$id_shop);
        } elseif (!in_array($source_id_shop, array(6, 13)) && $db->getValue('SELECT COUNT(0) FROM '._DB_PREFIX_.'st_advanced_menu WHERE location=2 AND id_shop='.$id_shop)) {
            $db->execute('UPDATE '._DB_PREFIX_.'st_advanced_menu SET active=0 WHERE location=2 AND id_shop='.$id_shop);
        }
    }
    
    public function import_rev_slider($version = '5.1.6')
    {
        if (version_compare($version, '5.1.6', '<')) {
            return false;
        }
        $sql_file = _PS_MODULE_DIR_.'stthemeeditor/config/rev_slider.sql';
        if (file_exists($sql_file)) {
            $db = Db::getInstance();
            if ($db->getValue('select count(0) from '._DB_PREFIX_.'revslider_sliders where `params` like "%displayFullWidthTop%"')) {
                return false;
            }
            $sql = @file_get_contents($sql_file);
            foreach(explode("\r", $sql) AS $sql) {
                $sql = str_replace('{_DB_PREFIX_}', _DB_PREFIX_, $sql);
                $db->execute($sql);
            }
        }
        return true;
    }
    
    private function process_reference($data, $new_id=0, $old_id, $table, $module)
    {
        if (!$new_id || !$table || !$old_id ||!$module)
            return $data;
        $i = 0;
        $module_tables = $this->module_tables;
        foreach($data AS $tbl => $rows) {
            if ($i++ <= 0 || !count($rows)) {
                continue;
            }
            if (in_array($table, $module_tables[$module][$tbl])) {
                foreach($rows AS $key => $row) {
                    if ((!isset($data[$tbl][$key]['done']) || !$data[$tbl][$key]['done']) && $data[$tbl][$key]['id_'.$table] == $old_id) {
                        $data[$tbl][$key]['id_'.$table] = $new_id;
                        $data[$tbl][$key]['done'] = 1;
                    }
                }
            }
        }
        // Update subitems.
        foreach($data[$table] AS $key => $row) {
            if (!isset($row['id_parent'])) {
                break;
            }
            if ($row['id_parent'] == $old_id) {
                $data[$table][$key]['id_parent'] = $new_id;
            }
        }
        
        return $data;
    }
    
    public function export_modules()
    {
        $content = array();
        foreach($this->hooks AS $hook) {
            $id_hook = Hook::get($hook);
            $modules = (array)Hook::getModulesFromHook($id_hook);
            foreach($modules AS $module) {
                $data = array();
                $content[$hook][$module['name']] = array();
                $data = $this->export_module_data($module['name']);
                if (count($data)) {
                    $content[$hook][$module['name']] = $data;
                }
            }
        }
        // Export global
        $global = array('sql'=>array(),'config'=>array());
        
        $field = Db::getInstance()->executeS('DESC '._DB_PREFIX_.'theme_meta id_shop');
        $left = Db::getInstance()->getValue('SELECT left_column FROM '._DB_PREFIX_.'theme_meta 
        WHERE id_meta=(SELECT id_meta FROM '._DB_PREFIX_.'meta WHERE page="index") 
        AND id_theme='.(int)Context::getContext()->theme->id.' 
        '.(is_array($field) && count($field) ? ' AND id_shop='.(int)Context::getContext()->shop->id : ''));
        
        $this->global['sql']['theme_meta|meta'] = 'UPDATE {theme_meta} SET left_column='.(int)$left.' 
        WHERE id_meta=(SELECT id_meta FROM {meta} WHERE page="index") 
        AND id_theme={id_theme}';
        
        foreach($this->global AS $key => $value) {
            foreach($value AS $k => $v) {
                if ($key == 'sql') {
                    $global['sql'][$k] = $v;
                } elseif ($key == 'config') {
                    $settings = Db::getInstance()->executeS('
                    SELECT name,value FROM 
                    (SELECT * FROM '._DB_PREFIX_.'configuration 
                    WHERE NAME LIKE "%'.$v.'%" 
                    AND (id_shop IS NULL OR id_shop = '.(int)Context::getContext()->shop->id.')
                    ORDER BY id_shop DESC) AS tmp  
                    GROUP BY name');
                    if (count($settings)) {
                        $global['config'] = array_merge($global['config'], $settings);
                    }
                }
            }
        }
        $content['global'] = $global;
        $content['source_id_shop'] = (int)Context::getContext()->shop->id;
        if (_ST_DEMO_DEBUG_) {
            @file_put_contents($this->_debug_file, print_r($content,true), FILE_APPEND);
        }
        return $content;
    }
    
    private function export_module_data($module)
    {
        if (!$module) {
            return false;
        }
        $data = array();
        $module_tables = $this->module_tables;
        $inst = Module::getInstanceByName($module);
        if (!is_object($inst) 
        || (strtoupper($inst->author) != 'SUNNYTOO.COM' 
        && strtoupper($inst->author) != 'PRESTASHOP'
        && $inst->name != 'revsliderprestashop') 
        || $inst->name == 'stthemeeditor') {
            return true;
        }
        // If module was disabled, only update the flag.
        if (Module::isInstalled($module) && Module::isEnabled($module)) {
            $data['disabled'] = 0;
        } else {
            $data['disabled'] = 1;
            return $data;
        }
        $db = Db::getInstance();
        // Export data from tables.
        $tables = isset($module_tables[$module]) && $module_tables[$module] ? $module_tables[$module] : '';
        if ($tables) {
            foreach($tables AS $table => $ref) {
                $sql = 'SELECT '._DB_PREFIX_.$table.'.* FROM '._DB_PREFIX_.$table;
                if (count($ref)) {
                    $sql .= $this->build_export_sql($tables, $ref[0], $table);    
                }
                $has_id_shop = false;
                foreach($tables AS $key => $value) {
                    if (strpos($key, '_shop') !== false) {
                        strpos($table, '_shop') === false && $sql .= ' LEFT JOIN '._DB_PREFIX_.$key.' ON ('._DB_PREFIX_.$key.'.id_'.$value[0].'='._DB_PREFIX_.$value[0].'.id_'.$value[0].')';
                        $sql .= ' WHERE '._DB_PREFIX_.$key.'.`id_shop`='.(int)Context::getContext()->shop->id;
                        if(strpos($table, '_shop') === false) {
                            $sql .= ' AND '._DB_PREFIX_.$value[0].'.`active` = 1';    
                        } else {
                            $main_table = str_replace('_shop', '', $table);
                            $sql .= ' AND id_'.$main_table.' IN (SELECT a.id_'.$main_table.' 
                            FROM '._DB_PREFIX_.$main_table.' a LEFT JOIN '._DB_PREFIX_.$table.' b ON a.id_'.$main_table.'=b.id_'.$main_table.' 
                            WHERE b.id_shop='.(int)Context::getContext()->shop->id.' 
                            AND a.`active` = 1)';
                        }
                        $has_id_shop = true;
                    }
                }
                if (!$has_id_shop) {
                    foreach($tables AS $key => $value) {
                        $sql .= ' WHERE '._DB_PREFIX_.$key.'.`id_shop`='.(int)Context::getContext()->shop->id;
                        $sql .= ' AND '._DB_PREFIX_.$key.'.`active` = 1';
                        break;
                    }
                }
                if (_ST_DEMO_DEBUG_) {
                    @file_put_contents($this->_debug_file, $sql."\r\n", FILE_APPEND);
                }
                $res = Db::getInstance()->executeS($sql);
                $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.$table.'` `id_parent`');
                if(is_array($field) && count($field)) {
                    foreach($res AS $value) {
                        if ($subs = $this->get_subs($table, $value['id_'.$table])) {
                            $res = array_merge($res, $subs);
                        }
                    }  
                }
                // If refer table has id_parent, fetch related rows.
                if (strpos('_lang', $table) === false && strpos('_shop', $table) === false && count($ref)) {
                    $field = Db::getInstance()->executeS('Describe `'._DB_PREFIX_.$ref[0].'` `id_parent`');
                    if (is_array($field) && count($field)) {
                        $ids = array();
                        foreach($data['sql'][$ref[0]] AS $_v) {
                            $ids[] = $_v['id_'.$ref[0]];
                        }
                        $ids2 = array();
                        foreach($res AS $_v) {
                            $ids2[] = $_v['id_'.$ref[0]];
                        }
                        if ($ids = array_diff($ids, $ids2)) {
                            $sql = 'SELECT * FROM '._DB_PREFIX_.$table.' WHERE id_'.$ref[0].' IN ('.implode(',',$ids).')';
                            if (_ST_DEMO_DEBUG_) {
                                @file_put_contents($this->_debug_file, $sql."\r\n", FILE_APPEND);
                            }
                            if ($r = Db::getInstance()->executeS($sql)) {
                                //$res = array_merge($res, $r);
                                $data['sql'][$table] = $r;
                                // Fetch data lang for this sub items.
                                $ids = array();
                                foreach($r AS $_v) {
                                    $ids[] = $_v['id_'.$table];
                                }
                                $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
                                $on_lang = ' AND id_lang='.(int)$lang->id;
                                $sql = 'SELECT * FROM '._DB_PREFIX_.$table.'_lang WHERE id_'.$table.' IN ('.implode(',',$ids).')'.$on_lang;
                                if (_ST_DEMO_DEBUG_) {
                                    @file_put_contents($this->_debug_file, $sql."\r\n", FILE_APPEND);
                                }
                                if ($res_lang = Db::getInstance()->executeS($sql)) {
                                    $data['sql'][$table.'_lang'] = $res_lang;
                                }
                            }
                            
                        }
                    }
                }
                if (!isset($data['sql'][$table]) || !$data['sql'][$table]) {
                    $data['sql'][$table] = array();     
                }
                if (is_array($res) && count($res)) {
                    $data['sql'][$table] = array_merge($res, $data['sql'][$table]);    
                }
            }
            // Remove this module tables as they were imported.
            unset($this->module_tables[$module]);
        }
        // Export module settings.
        if (method_exists($inst,'get_prefix') && $prefix = $inst->get_prefix()) {
            $settings = $db->executeS('
            SELECT name,value FROM 
            (SELECT * FROM '._DB_PREFIX_.'configuration 
            WHERE NAME LIKE "%'.$prefix.'%" 
            AND (id_shop IS NULL OR id_shop = '.(int)Context::getContext()->shop->id.')
            ORDER BY id_shop DESC) AS tmp  
            GROUP BY name');
            if (count($settings)) {
                $data['config'] = $settings;
            }
        }
        
        return $data;
    }

    private function build_export_sql($table_map, $join_table, $main_table)
    {
        if (strpos($main_table,'_shop') !== false) {
            return '';
        }
        if (!$table_map || !$join_table || !$main_table) {
            return '';
        }
        $on_lang = '';
        if (strpos($main_table, '_lang') !== false) {
            $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
            $on_lang = ' AND id_lang='.(int)$lang->id;
        }
        $sql = ' Left JOIN ' . _DB_PREFIX_.$join_table . ' 
        ON ('._DB_PREFIX_.$join_table.'.id_'.$join_table.'='._DB_PREFIX_.$main_table.'.id_'.$join_table.$on_lang.')';
        if (count($table_map[$join_table])) {
            $sql .= $this->build_export_sql($table_map, $table_map[$join_table][0], $join_table);
        }
        return $sql;
    }
    
    private function get_subs($table='', $id=0)
    {
        $ret = array();
        if (!$table || !$id) {
            return $ret;
        }
        $sql = 'SELECT * FROM '._DB_PREFIX_.$table.' WHERE id_parent='.(int)$id;
        if (_ST_DEMO_DEBUG_) {
            @file_put_contents($this->_debug_file, $sql."\r\n", FILE_APPEND);
        }
        if($ret = Db::getInstance()->executeS($sql)) {
            foreach($ret AS $value) {
                $ret = array_merge($ret, $this->get_subs($table, $value['id_'.$table]));
            }
        }
        return $ret;
    }

}