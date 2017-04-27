{if $banner_data['url']}
    <a id="st_owl_carousel_block_{$banner_data['id_st_owl_carousel']}" href="{$banner_data['url']|escape:'html'}" class="st_owl_carousel_block_{$banner_data['id_st_owl_carousel']} st_owl_carousel_block" target="{if $banner_data['new_window']}_blank{else}_self{/if}" title="{$banner_data['title']|escape:'htmlall':'UTF-8'}">
{else}
    <div id="st_owl_carousel_block_{$banner_data['id_st_owl_carousel']}" class="st_owl_carousel_block_{$banner_data['id_st_owl_carousel']} st_owl_carousel_block">
{/if}
<img class="st_owl_carousel_image" src="{$banner_data['image_multi_lang']}" alt="{$banner_data['title']|escape:'htmlall':'UTF-8'}" {if (isset($banner_data['width']) && $banner_data['width'])}width="{$banner_data['width']}"{/if} {if (isset($banner_data['height']) && $banner_data['height'])}height="{$banner_data['height']}"{/if} />
{if $banner_data['description']}
    <div class="st_owl_carousel_text text_table_wrap {if $banner_data.hide_text_on_mobile} hidden-xs {/if}">
        <div class="text_table">
            <div class="text_td style_content {if $banner_data.text_align==1} text-left {elseif $banner_data.text_align==3} text-right {else} text-center {/if} banner_text_{$banner_data.text_position|default:'center'} clearfix">
                {if isset($banner_data.text_width) && $banner_data.text_width}<div class="text_inner_box {if $banner_data.text_width>10 && $banner_data.text_width<20} text_inner_box_left{elseif $banner_data.text_width>20 && $banner_data.text_width<30} text_inner_box_right{/if} center_width_{$banner_data.text_width%10}0">{/if}
                {if $banner_data['description']}{$banner_data['description']}{/if}
                {if isset($banner_data.text_width) && $banner_data.text_width}</div>{/if}
            </div>
        </div>
    </div>
{/if}
{if $banner_data['url']}
    </a>
{else}
    </div>
{/if}