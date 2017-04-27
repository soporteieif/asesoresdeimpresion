CREATE TABLE IF NOT EXISTS `PREFIX_st_easy_tabs` (                               
    `id_st_easy_tabs` int(10) NOT NULL AUTO_INCREMENT,
    `id_shop` int(10) unsigned NOT NULL,    
    `id_category` int(10) unsigned NOT NULL DEFAULT 0,
    `id_product` int(10) unsigned NOT NULL DEFAULT 0,
    `id_product_specific` int(10) unsigned NOT NULL DEFAULT 0,
    `id_manufacturer` int(10) unsigned NOT NULL DEFAULT 0,
    `allitems` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `active` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `position` int(10) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_st_easy_tabs`)                              
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `PREFIX_st_easy_tabs_lang` (                               
    `id_st_easy_tabs` int(10) unsigned NOT NULL,   
    `id_lang` int(10) unsigned NOT NULL,    
    `title` varchar(255) DEFAULT NULL,           
    `content` text,        
    PRIMARY KEY (`id_st_easy_tabs`,`id_lang`)                          
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;