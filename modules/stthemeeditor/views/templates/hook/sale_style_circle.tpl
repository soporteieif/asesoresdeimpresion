<span class="sale_percentage_sticker img-circle">
    {if $percentage_amount=='percentage'}
        {$reduction*100|floatval}%<br />{l s='Off' mod='stthemeeditor'}
    {elseif $percentage_amount=='amount'}
        {l s='Save' mod='stthemeeditor'}<br />{convertPrice price=$price_without_reduction-$price|floatval}
    {/if}
</span>