<!-- MODULE stiossldier -->
{if isset($slide_group)}
    {if isset($google_font_links)}{$google_font_links}{/if}
    {foreach $slide_group as $slide}
        {if $slide['templates']==1}
            {include file="./stiosslider-fullwidth-boxed.tpl"}
        {else if $slide['templates']==2}
            {include file="./stiosslider-center-background.tpl"}
        {else if $slide['templates']==3}
            {include file="./stiosslider-multi-slide.tpl"}
        {else}
            {include file="./stiosslider-fullwidth.tpl"}
        {/if}
    {/foreach}
{/if}
<!--/ MODULE stiossldier -->