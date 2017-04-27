<!-- MODULE stowlcarousel -->
{if isset($slides)}
    {if isset($slides['slide']) && count($slides['slide'])}
        <div id="st_owl_carousel-{$slides.id_st_owl_carousel_group}" class="{if count($slides['slide'])>1} owl-carousel owl-theme owl-navigation-lr {if $slides['prev_next']==2} owl-navigation-rectangle {elseif $slides['prev_next']==3} owl-navigation-circle {elseif $slides['prev_next']==4} owl-navigation-square {/if}{/if}">
            {foreach $slides['slide'] as $banner}
                {include file="./stowlcarousel-block.tpl" banner_data=$banner}
            {/foreach}
        </div>
        {include file="./stowlcarousel-script.tpl" js_data=$slides}
    {/if}
{/if}
<!--/ MODULE stowlcarousel -->