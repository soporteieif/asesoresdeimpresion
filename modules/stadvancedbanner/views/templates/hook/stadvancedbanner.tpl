<!-- MODULE st banner -->
{if isset($groups)}
    {foreach $groups as $group}
        {assign var='style' value=0}
        {if isset($group.style) && $group.style}{$style=1}{/if}
        {if $group.is_full_width}<div id="advanced_banner_container_{$group.id_st_advanced_banner_group}" class="advanced_banner_container full_container {if $group['hide_on_mobile']} hidden-xs {/if} block">{if !$group.stretched}<div class="container">{/if}{/if}
            <div id="st_advanced_banner_{$group.id_st_advanced_banner_group}" class="st_advanced_banner_row st_advanced_banner_{$style} {if !$group.is_full_width} block {/if} {if $group['hide_on_mobile']} hidden-xs {/if}{if $group['hover_effect']} hover_effect_{$group['hover_effect']} {/if} {if isset($group.is_column) && $group.is_column} column_block {/if}">
                {if isset($group['banners']) && count($group['banners'])}
                    <div class="row block_content">
                        <div id="advanced_banner_box_{$group['id_st_advanced_banner_group']}" class="col-sm-12 advanced_banner_col" data-height="100">
                            {include file="./stadvancedbanner-block.tpl" banner_data=$group['banners'][0] banner_height=$group['height'] banner_style=$style}
                        </div>
                    </div>
                {elseif isset($group['columns'])}
                    {include file="./stadvancedbanner-column.tpl" columns_data=$group['columns'] banner_style=$style}
                {/if}
            </div>
        {if $group.is_full_width}</div>{if !$group.stretched}</div>{/if}{/if}
    {/foreach}
{/if}
<!--/ MODULE st banner -->