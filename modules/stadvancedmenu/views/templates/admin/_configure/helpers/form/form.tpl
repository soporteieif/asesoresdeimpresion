{*
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
*}
{extends file="helpers/form/form.tpl"}

{block name="field"}
	{if $input.type == 'dropdownlistgroup'}
		<div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}9{/if} {if !isset($input.label)}col-lg-offset-3{/if} fontello_wrap">
			<div class="row">
				{foreach $input.values.medias AS $media}
					<div class="col-xs-4 col-sm-3">
						<label data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="{if $media=='lg'}{l s='Desktops (>1200px)'}{elseif $media=='md'}{l s='Desktops (>992px)'}{elseif $media=='sm'}{l s='Tablets (>768px)'}{elseif $media=='xs'}{l s='Phones (>480px)'}{elseif $media=='xxs'}{l s='Phones (<480px)'}{/if}">{if $media=='lg'}{l s='Large devices'}{elseif $media=='md'}{l s='Medium devices'}{elseif $media=='sm'}{l s='Small devices'}{elseif $media=='xs'}{l s='Extra small devices'}{elseif $media=='xxs'}{l s='Extremely small devices'}{/if}</label>

						<select name="{$input.name}_{$media}" id="{$input.name}_{$media}" class="fixed-width-md">
            			{for $foo=1 to $input.values.maximum}
	                        <option value="{$foo}" {if $fields_value[$input['name']|cat:"_"|cat:$media] == $foo} selected="selected" {/if}>{$foo}</option>
	                    {/for}
            			</select>
					</div>
				{/foreach}
			</div>
			{if isset($input.desc) && !empty($input.desc)}
				<p class="help-block">
					{if is_array($input.desc)}
						{foreach $input.desc as $p}
							{if is_array($p)}
								<span id="{$p.id}">{$p.text}</span><br />
							{else}
								{$p}<br />
							{/if}
						{/foreach}
					{else}
						{$input.desc}
					{/if}
				</p>
			{/if}
		</div>
	{elseif $input.type == 'fontello'}
		<div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}9{/if} {if !isset($input.label)}col-lg-offset-3{/if} fontello_wrap">
			<a id="btn_{$input.name}" class="btn btn-default" data-toggle="modal" href="#" data-target="#modal_fontello">
				<i class="{$fields_value[$input.name]}"></i>{l s='Edit'}
			</a>
			<div class="modal fade" id="modal_fontello" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">{l s="Icon"}</h4>
					</div>
					<div class="modal-body">
						<ul class="fontello_list clearfix">
							<li>
								<label>
									<input type="radio"	name="{$input.name}" id="{$input.name}" value=""{if $fields_value[$input.name] == ''} checked="checked"{/if}/>
									{l s="None"}
								</label>
							</li>
							{foreach $input.values.classes AS $class}
								<li>
									<label>
										<input type="radio"	name="{$input.name}" id="{$input.name}_{$class}" value="{$class|escape:'html':'UTF-8'}"{if $fields_value[$input.name] == $class} checked="checked"{/if}/>
										<i class="{$class}"></i>
									</label>
								</li>
							{/foreach}
						</ul>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">{l s="OK"}</button>
					</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(function($){
					$("input[name={$input.name}]").change(function() { 
						$("#btn_{$input.name} i").removeClass().addClass($("input[name={$input.name}]:checked").val());
					});
				});
			</script>
			<style type="text/css">
				@font-face {
				  font-family: 'fontello';
				  src: url('{$input.values.module_name}../../themes/{$input.values.theme_name}/font/fontello.eot?123');
				  src: url('{$input.values.module_name}../../themes/{$input.values.theme_name}/font/fontello.eot#iefix?123') format('embedded-opentype'),
				       url('{$input.values.module_name}../../themes/{$input.values.theme_name}/font/fontello.woff?123') format('woff'),
				       url('{$input.values.module_name}../../themes/{$input.values.theme_name}/font/fontello.ttf?123') format('truetype'),
				       url('{$input.values.module_name}../../themes/{$input.values.theme_name}/font/fontello.svg#fontello?123') format('svg');
				  font-weight: normal;
				  font-style: normal;
				}
				{$input.values.css}
			</style>
		</div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}