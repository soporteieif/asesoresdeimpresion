{if $banner_data['url']}
    <a id="st_advanced_banner_block_{$banner_data['id_st_advanced_banner']}" href="{$banner_data['url']|escape:'html'}" class="st_advanced_banner_block_{$banner_data['id_st_advanced_banner']} st_advanced_banner_block" target="{if $banner_data['new_window']}_blank{else}_self{/if}" title="{$banner_data['title']|escape:'htmlall':'UTF-8'}" style="{if !$banner_style}height:{$banner_height}px;{/if}">
{else}
    <div id="st_advanced_banner_block_{$banner_data['id_st_advanced_banner']}" class="st_advanced_banner_block_{$banner_data['id_st_advanced_banner']} st_advanced_banner_block" style="{if !$banner_style}height:{$banner_height}px;{/if}">
{/if}
{if $banner_style}
    <img src="{$banner_data['image_multi_lang']}" alt="{$banner_data['title']|escape:'htmlall':'UTF-8'}" class="st_advanced_banner_static_image" />
{else}
    <div class="st_advanced_banner_image" style="{if isset($banner_data['image_multi_lang']) && $banner_data['image_multi_lang']}background-image:url({$banner_data['image_multi_lang']});{/if}{if isset($banner_data['bg_color']) && $banner_data['bg_color']}background-color:{$banner_data['bg_color']};{/if}"></div>
    {if $banner_data['description']}
        <div class="advanced_banner_text text_table_wrap {if $banner_data.hide_text_on_mobile} hidden-xs {/if}">
            <div class="text_table">
                <div class="text_td style_content {if $banner_data.text_align==1} text-left {elseif $banner_data.text_align==3} text-right {else} text-center {/if} advanced_banner_text_{$banner_data.text_position|default:'center'} clearfix">
                    {if isset($banner_data.text_width) && $banner_data.text_width}<div class="text_inner_box {if $banner_data.text_width>10 && $banner_data.text_width<20} text_inner_box_left{elseif $banner_data.text_width>20 && $banner_data.text_width<30} text_inner_box_right{/if} center_width_{$banner_data.text_width%10}0">{/if}
                    {if $banner_data['description']}{$banner_data['description']}{/if}
                    {if isset($banner_data.text_width) && $banner_data.text_width}</div>{/if}
                </div>
            </div>
        </div>
    {/if}
{/if}
{if $banner_data['url']}
    </a>
{else}
    </div>
{/if}