<!-- MODULE st banner -->
{if isset($group)}
    {foreach $group as $banner}
        {if isset($banner['banner'])}
            {assign var=banner_count value=count($layout[$banner['layout']])}
            {if $banner['location']==6 || $banner['location']==18}<div id="banner_container_{$banner.id_st_banner_group}" class="wide_container {if $banner['hide_on_mobile']} hidden-xs {/if}"><div class="container">{/if}
            <div id="banner_{$banner.id_st_banner_group}" class="row st_banner_block mar_b2 {if $banner['hide_on_mobile']} hidden-xs {/if}">
                {for $foo=0 to $banner_count-1}
                    <div class="{$layout[$banner['layout']][$foo]} text-center st_banner_image">
                        {if isset($banner['banner'][$foo])}
                            {if $banner['banner'][$foo]['url']}
                                <a href="{$banner['banner'][$foo]['url']|escape:'html'}" target="{if $banner['banner'][$foo]['new_window']}_blank{else}_self{/if}" title="{$banner['banner'][$foo]['title']|escape:'htmlall':'UTF-8'}">
                            {/if}
                            <img class="{if $banner['hover_effect']} hover_effect {/if}" src="{if (isset($banner['banner'][$foo]['image_multi_lang']) && $banner['banner'][$foo]['image_multi_lang'])}{$banner['banner'][$foo]['image_multi_lang']}{else}{$banner['banner'][$foo]['image']}{/if}" alt="{$banner['banner'][$foo]['title']|escape:'htmlall':'UTF-8'}" {if (isset($banner['banner'][$foo]['width']) && $banner['banner'][$foo]['width'])}width="{$banner['banner'][$foo]['width']}"{/if} {if (isset($banner['banner'][$foo]['height']) && $banner['banner'][$foo]['height'])}height="{$banner['banner'][$foo]['height']}"{/if} />
                            {if $banner['banner'][$foo]['url']}
                                </a>
                            {/if}
                        {/if}
                    </div>
                {/for}
            </div>
            {if $banner['location']==6 || $banner['location']==18}</div></div>{/if}
        {/if}
    {/foreach}
{/if}
<!--/ MODULE st banner -->