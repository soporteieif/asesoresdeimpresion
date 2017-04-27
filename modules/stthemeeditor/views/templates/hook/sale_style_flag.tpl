<span class="sale_percentage">
    <i class="icon-tag"></i>{if $percentage_amount=='percentage'}-{$reduction*100|floatval}%{elseif $percentage_amount=='amount'}-{convertPrice price=$price_without_reduction-$price|floatval}{/if}
</span>