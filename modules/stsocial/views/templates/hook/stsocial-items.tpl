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
<!-- MODULE st social  -->
{capture name="social_target"}{if (!isset($social_new_window) || $social_new_window)} target="_blank" {/if}{/capture}

{if $facebook_url != ''}<li><a id="stsocial_facebook" href="{$facebook_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Facebook' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-facebook icon-large"></i></a></li>{/if}
{if $twitter_url != ''}<li><a id="stsocial_twitter" href="{$twitter_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Twitter' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-twitter  icon-large"></i></a></li>{/if}
{if $rss_url != ''}<li><a id="stsocial_rss" href="{$rss_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='RSS' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-rss icon-large"></i></a></li>{/if}
{if $youtube_url != ''}<li><a id="stsocial_youtube" href="{$youtube_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Youtube' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-youtube icon-large"></i></a></li>{/if}
{if $pinterest_url != ''}<li><a id="stsocial_pinterest" href="{$pinterest_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Pinterest' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-pinterest icon-large"></i></a></li>{/if}
{if $google_url != ''}<li><a id="stsocial_google" href="{$google_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Google' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-gplus icon-large"></i></a></li>{/if}
{if $wordpress_url != ''}<li><a id="stsocial_wordpress" href="{$wordpress_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Wordpress' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-wordpress icon-large"></i></a></li>{/if}
{if $drupal_url != ''}<li><a id="stsocial_drupal" href="{$drupal_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Drupal' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-drupal icon-large"></i></a></li>{/if}
{if $vimeo_url != ''}<li><a id="stsocial_vimeo" href="{$vimeo_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Vimeo' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-vimeo icon-large"></i></a></li>{/if}
{if $flickr_url != ''}<li><a id="stsocial_flickr" href="{$flickr_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Flickr' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-flickr icon-large"></i></a></li>{/if}
{if $digg_url != ''}<li><a id="stsocial_digg" href="{$digg_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Digg' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-digg icon-large"></i></a></li>{/if}
{if $eaby_url != ''}<li><a id="stsocial_ebay" href="{$eaby_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Ebay' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-ebay icon-large"></i></a></li>{/if}
{if $amazon_url != ''}<li><a id="stsocial_amazon" href="{$amazon_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Amazon' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-amazon icon-large"></i></a></li>{/if}
{if $instagram_url != ''}<li><a id="stsocial_instagram" href="{$instagram_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Instagram' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-instagram icon-large"></i></a></li>{/if}
{if $linkedin_url != ''}<li><a id="stsocial_linkedin" href="{$linkedin_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='LinkedIn' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-linkedin icon-large"></i></a></li>{/if}
{if $blogger_url != ''}<li><a id="stsocial_blogger" href="{$blogger_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Blogger' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-blogger icon-large"></i></a></li>{/if}
{if $tumblr_url != ''}<li><a id="stsocial_tumblr" href="{$tumblr_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Tumblr' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-tumblr icon-large"></i></a></li>{/if}
{if $vkontakte_url != ''}<li><a id="stsocial_vkontakte" href="{$vkontakte_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Vkontakte' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-vk icon-large"></i></a></li>{/if}
{if $skype_url != ''}<li><a id="stsocial_skype" href="{$skype_url|escape:html:'UTF-8'}" rel="nofollow" title="{l s='Skype' mod='stsocial'}" {$smarty.capture.social_target}><i class="icon-skype icon-large"></i></a></li>{/if}
