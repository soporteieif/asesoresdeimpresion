{if $mm.is_mega}
<div class="{if !isset($is_mega_menu_vertical)}stadvancedmenu_sub{else}stadvancedmenu_vs{/if} advanced_style_wide col-md-{($mm.width*10/10)|replace:'.':'-'}">
	<div class="row advanced_m_column_row">
	{assign var='t_width_tpl' value=0}
	{foreach $mm.column as $column}
		{if $column.hide_on_mobile == 2}{continue}{/if}
		{if isset($column.children) && count($column.children)}
		{assign var="t_width_tpl" value=$t_width_tpl+$column.width}
		{if $t_width_tpl>$mm.t_width}
			{assign var="t_width_tpl" value=$column.width}
			</div><div class="row advanced_m_column_row">
		{/if}
		<div id="st_advanced_menu_column_{$column.id_st_advanced_column}" class="col-md-{($column.width*10/10)|replace:'.':'-'}">
			{foreach $column.children as $block}
				{if $block.hide_on_mobile == 2}{continue}{/if}
				{if $block.item_t==1}
					{if $block.subtype==2  && isset($block.children)}
						<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}">
							{if $block.show_cate_img}
			                    {include file="./stadvancedmenu-cate-img.tpl" menu_cate=$block.children nofollow=$block.nofollow new_window=$block.new_window}
							{/if}
							<ul class="advanced_mu_level_1">
								<li class="advanced_ml_level_1">
									<a id="st_advanced_ma_{$block.id_st_advanced_menu}" href="{$block.children.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$block.children.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item">{$block.children.name|escape:'html':'UTF-8'}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
									{if isset($block.children.children) && is_array($block.children.children) && count($block.children.children)}
										<ul class="advanced_mu_level_2">
										{foreach $block.children.children as $product}
										<li class="advanced_ml_level_2"><a href="{$product.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$product.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_2 advanced_ma_item">{$product.name|truncate:30:'...'|escape:'html':'UTF-8'}</a></li>
										{/foreach}
										</ul>	
									{/if}
								</li>
							</ul>	
						</div>
					{elseif $block.subtype==0  && isset($block.children.children) && count($block.children.children)}
						<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}">
						<div class="row">
						{foreach $block.children.children as $menu}
							<div class="col-md-{((12/$block.items_md)*10/10)|replace:'.':'-'}">
								{if $block.show_cate_img}
				                    {include file="./stadvancedmenu-cate-img.tpl" menu_cate=$menu nofollow=$block.nofollow new_window=$block.new_window}
								{/if}
								<ul class="advanced_mu_level_1">
									<li class="advanced_ml_level_1">
										<a href="{$menu.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$menu.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item">{$menu.name|escape:'html':'UTF-8'}</a>
										{if isset($menu.children) && is_array($menu.children) && count($menu.children)}
											{assign var='granditem' value=0}
											{if isset($block.granditem) && $block.granditem}{$granditem=1}{/if}
											{include file="./stadvancedmenu-category.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$menu.children granditem=$granditem m_level=2}
										{/if}
									</li>
								</ul>	
							</div>
							{if $menu@iteration%$block.items_md==0 && !$menu@last}
							</div><div class="row">
							{/if}
						{/foreach}
						</div>
						</div>
					{elseif $block.subtype==1 || $block.subtype==3}
						<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}">
							{if $block.show_cate_img}
			                    {include file="./stadvancedmenu-cate-img.tpl" menu_cate=$block.children nofollow=$block.nofollow new_window=$block.new_window}
							{/if}
							<ul class="advanced_mu_level_1">
								<li class="advanced_ml_level_1">
									<a id="st_advanced_ma_{$block.id_st_advanced_menu}" href="{$block.children.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$block.children.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item">{$block.children.name|escape:'html':'UTF-8'}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
									{if $block.subtype==1 && isset($block.children.children) && is_array($block.children.children) && count($block.children.children)}
										{assign var='granditem' value=0}
										{if isset($block.granditem) && $block.granditem}{$granditem=1}{/if}
										{include file="./stadvancedmenu-category.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$block.children.children granditem=$granditem m_level=2}
									{/if}
								</li>
							</ul>	
						</div>
					{/if}
				{elseif $block.item_t==2 && isset($block.children) && count($block.children)}
					<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}" class="row">
					{foreach $block.children as $product}
						<div class="col-md-{((12/$block.items_md)*10/10)|replace:'.':'-'}">
							<div class="pro_outer_box">
							<div class="pro_first_box">
								<a class="product_img_link"	href="{$link->getProductLink($product->id, $product->link_rewrite, $product->category)|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$product->name|escape:'html':'UTF-8'}"{/if} itemprop="url"{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} >
									<img class="replace-2x img-responsive menu_pro_img" src="{$link->getImageLink($product->link_rewrite, $product->id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product->legend)}{$product->legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" {if !$adv_menu_title} title="{if !empty($product->legend)}{$product->legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"{/if} {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
									{if $new_sticker!=2 && $product->is_new}<span class="new"><i>{l s='New' mod='stadvancedmenu'}</i></span>{/if}
									{if $sale_sticker!=2 && !$PS_CATALOG_MODE && isset($product->on_sale) && $product->on_sale && isset($product->show_price) && $product->show_price}<span class="on_sale"><i>{l s='Sale' mod='stadvancedmenu'}</i></span>{/if}
								</a>
							</div>
							<div class="pro_second_box">
								<p class="s_title_block">
								<a class="product-name" href="{$link->getProductLink($product->id, $product->link_rewrite, $product->category)|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$product->name|escape:'html':'UTF-8'}"{/if} itemprop="url"{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if} >
									{$product->name|truncate:45:'...'|escape:'html':'UTF-8'}
								</a>
								</p>
								<div class="price_container" >
									{if isset($product->show_price) && $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}<span class="price product-price">{convertPrice price=$product->displayed_price}</span>{if isset($product->specificPrice) && $product->specificPrice && isset($product->specificPrice['reduction']) && $product->specificPrice['reduction'] > 0 && isset($product->price_without_reduction) && $product->price_without_reduction}<span class="old_price">{convertPrice price=$product->price_without_reduction}</span>{/if}
				                    {/if}
								</div>
							</div>
							</div>
						</div>
					{/foreach}
					</div>
				{elseif $block.item_t==3 && isset($block.children) && count($block.children)}
					{if isset($block.subtype) && $block.subtype}
						<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}">
						<div class="row">
						{foreach $block.children as $brand}
							<div class="col-md-{((12/$block.items_md)*10/10)|replace:'.':'-'}">
								<ul class="advanced_mu_level_1">
									<li class="advanced_ml_level_1">
										<a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" {if !$adv_menu_title} title="{$brand.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item">{$brand.name|escape:'html':'UTF-8'}</a>
									</li>
								</ul>	
							</div>
							{if $brand@iteration%$block.items_md==0 && !$brand@last}
							</div><div class="row">
							{/if}
						{/foreach}
						</div>
						</div>
					{else}
						<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}" class="row">
						{foreach $block.children as $brand}
							<div class="col-md-{((12/$block.items_md)*10/10)|replace:'.':'-'}">
								<a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" {if !$adv_menu_title} title="{$brand.name|escape:html:'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="st_advanced_menu_brand">
				                    <img src="{$img_manu_dir}{$brand.id_manufacturer|escape:'htmlall':'UTF-8'}-manufacturer_default.jpg" alt="{$brand.name|escape:html:'UTF-8'}" width="{$manufacturerSize.width}" height="{$manufacturerSize.height}" class="replace-2x img-responsive" />
				                </a>
							</div>
						{/foreach}
						</div>
					{/if}
				{elseif $block.item_t==4}
					<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}">
						<ul class="advanced_mu_level_1">
							<li class="advanced_ml_level_1">
								<a id="st_advanced_ma_{$block.id_st_advanced_menu}" href="{if $block.m_link}{$block.m_link|escape:'html':'UTF-8'}{else}javascript:;{/if}" {if !$adv_menu_title} title="{$block.m_title|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item {if !$block.m_link} advanced_ma_span{/if}">{if $block.icon_class}<i class="{$block.icon_class}"></i>{/if}{$block.m_name|escape:'html':'UTF-8'}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
								{if isset($block.children) && is_array($block.children) && count($block.children)}
									{foreach $block.children as $menu}
										{if $menu.hide_on_mobile == 2}{continue}{/if}
										<ul class="advanced_mu_level_2 granditem">
										{include file="./stadvancedmenu-link.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$menu m_level=2}
										</ul>
									{/foreach}
								{/if}
							</li>
						</ul>	
					</div>
				{elseif $block.item_t==5 && $block.html}
					<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}" class="style_content">
						{$block.html}
					</div>
				{/if}
			{/foreach}
		</div>
		{/if}
	{/foreach}
	</div>
</div>
{else}
	<ul id="st_advanced_menu_multi_level_{$mm.id_st_advanced_menu}" class="{if !isset($is_mega_menu_vertical)}stadvancedmenu_sub{else}stadvancedmenu_vs{/if} stadvancedmenu_multi_level">
	{foreach $mm.column as $column}
		{if $column.hide_on_mobile == 2}{continue}{/if}
		{if isset($column.children) && count($column.children)}
			{foreach $column.children as $block}
				{if $block.hide_on_mobile == 2}{continue}{/if}
				{if $block.item_t==1}
					{if $block.subtype==2  && isset($block.children) && count($block.children)}
						{if isset($block.children.children) && is_array($block.children.children) && count($block.children.children)}
							{foreach $block.children.children as $product}
							<li class="advanced_ml_level_1"><a href="{$product.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$product.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item">{$product.name|truncate:30:'...'|escape:'html':'UTF-8'}</a></li>
							{/foreach}
						{/if}
					{elseif $block.subtype==0  && isset($block.children.children) && count($block.children.children)}
						{foreach $block.children.children as $menu} 
							<li class="advanced_ml_level_1">
								{assign var='has_children' value=(isset($menu.children) && is_array($menu.children) && count($menu.children))}
								<a href="{$menu.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$menu.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item {if $has_children} has_children {/if}">{$menu.name|escape:'html':'UTF-8'}{if $has_children}<span class="is_parent_icon"><b class="is_parent_icon_h"></b><b class="is_parent_icon_v"></b></span>{/if}</a>
								{if $has_children}
									{include file="./stadvancedmenu-category.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$menu.children m_level=2}
								{/if}
							</li>
						{/foreach}
					{elseif $block.subtype==1 || $block.subtype==3}
						<li class="advanced_ml_level_1">
							{assign var='has_children' value=(isset($block.children.children) && count($block.children.children))}
							<a id="st_advanced_ma_{$block.id_st_advanced_menu}" href="{$block.children.link|escape:'html':'UTF-8'}" {if !$adv_menu_title} title="{$block.children.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item {if $has_children} has_children {/if}">{$block.children.name|escape:'html':'UTF-8'}{if $has_children}<span class="is_parent_icon"><b class="is_parent_icon_h"></b><b class="is_parent_icon_v"></b></span>{/if}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
							{if $has_children}
								{include file="./stadvancedmenu-category.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$block.children.children m_level=2}
							{/if}
						</li>
					{/if}
				{elseif $block.item_t==3 && isset($block.children) && count($block.children)}
					<li class="advanced_ml_level_1">
					{if isset($block.subtype) && $block.subtype}
						{foreach $block.children as $brand}
							<a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" {if !$adv_menu_title} title="{$brand.name|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item">{$brand.name|escape:'html':'UTF-8'}</a>
						{/foreach}
					{else}
						{foreach $block.children as $brand}
							<a href="{$link->getmanufacturerLink($brand.id_manufacturer, $brand.link_rewrite)}" {if !$adv_menu_title} title="{$brand.name|escape:html:'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="st_advanced_menu_brand">
			                    <img src="{$img_manu_dir}{$brand.id_manufacturer|escape:'htmlall':'UTF-8'}-manufacturer_default.jpg" alt="{$brand.name|escape:html:'UTF-8'}" width="{$manufacturerSize.width}" height="{$manufacturerSize.height}" class="replace-2x img-responsive" />
			                </a>
						{/foreach}
					{/if}
					</li>
				{elseif $block.item_t==4}
					<li class="advanced_ml_level_1">
						{assign var='has_children' value=(isset($block.children) && is_array($block.children) && count($block.children))}
						<a id="st_advanced_ma_{$block.id_st_advanced_menu}" href="{if $block.m_link}{$block.m_link|escape:'html':'UTF-8'}{else}javascript:;{/if}" {if !$adv_menu_title} title="{$block.m_title|escape:'html':'UTF-8'}"{/if}{if $block.nofollow} rel="nofollow"{/if}{if $block.new_window} target="_blank"{/if}  class="advanced_ma_level_1 advanced_ma_item {if $has_children} has_children {/if}{if !$block.m_link} advanced_ma_span{/if}">{if $block.icon_class}<i class="{$block.icon_class}"></i>{/if}{$block.m_name|escape:'html':'UTF-8'}{if $has_children}<span class="is_parent_icon"><b class="is_parent_icon_h"></b><b class="is_parent_icon_v"></b></span>{/if}{if $block.cate_label}<span class="cate_label">{$block.cate_label}</span>{/if}</a>
						{if $has_children}
							{foreach $block.children as $menu}
								{if $menu.hide_on_mobile == 2}{continue}{/if}
								<ul class="advanced_mu_level_2">
								{include file="./stadvancedmenu-link.tpl" nofollow=$block.nofollow new_window=$block.new_window menus=$menu m_level=2}
								</ul>
							{/foreach}
						{/if}
					</li>
				{elseif $block.item_t==5 && $block.html}
					<li class="advanced_ml_level_1">
						<div id="st_advanced_menu_block_{$block.id_st_advanced_menu}" class="style_content">
							{$block.html}
						</div>
					</li>
				{else}
					
				{/if}
			{/foreach}
		{/if}
	{/foreach}
	</ul>
{/if}