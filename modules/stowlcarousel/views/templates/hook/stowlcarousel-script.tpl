<script type="text/javascript">
//<![CDATA[
{literal}
    jQuery(function($){
        {/literal}{if count($js_data['slide'])>1}{literal}
        $("#st_owl_carousel-{/literal}{$js_data.id_st_owl_carousel_group}{literal}").owlCarousel({
            {/literal}
            autoPlay : {if $js_data.auto_advance && !$js_data.progress_bar}{$js_data.time|default:5000}{else}false{/if},
            navigation: {if $js_data.prev_next}true{else}false{/if},
            pagination: {if $js_data.pag_nav}true{else}false{/if},
            paginationSpeed : 1000,
            goToFirstSpeed : 2000,
            rewindNav: {if $js_data.rewind_nav}true{else}false{/if},
            singleItem : {if $js_data.templates!=3}true{else}false{/if},
            {if $js_data.templates==3}
              {literal}
              itemsCustom : [
                {/literal}{if $sttheme.responsive && !$sttheme.version_switching}{literal}
                [0,{/literal}{$js_data.items_xxs}{literal}],
                [460,{/literal}{$js_data.items_xs}{literal}],
                [748,{/literal}{$js_data.items_sm}{literal}],
                [972,{/literal}{$js_data.items_md}{literal}],
                [1180,{/literal}{$js_data.items_lg}{literal}],
                [1420,{/literal}{$js_data.items_xlg}{literal}],
                {/literal}{else}{literal}
                [0,{/literal}{if $sttheme.responsive_max==2}{$js_data.items_xlg}{elseif $sttheme.responsive_max==1}{$js_data.items_lg}{else}{$js_data.items_md}{/if}{literal}],
                {/literal}{/if}{literal}
                [1600,{/literal}{$js_data.items_xxlg}{literal}],
                [1900,{/literal}{$js_data.items_huge}{literal}]
              ],
              {/literal}
            {/if}
            autoHeight : {if $js_data.auto_height}true{else}false{/if},
            slideSpeed: {$js_data.trans_period|default:200},
            stopOnHover: {if $js_data.pause}true{else}false{/if},
            mouseDrag: {if $js_data.mouse_drag}true{else}false{/if},
            {if $js_data.progress_bar}
            afterInit : st_owl_progressBar,
            afterMove : st_owl_moved,
            startDragging : st_owl_pauseOnDragging,
            {/if}
            transitionStyle: "{if array_key_exists($js_data.transition_style, $transition_style)}{$transition_style[$js_data.transition_style]['name']}{else}fade{/if}"
            {literal}
        });
        {/literal}{if $js_data.progress_bar}{literal}
        var st_owl_time = {/literal}{$js_data.time|default:5000}{literal}; // time in seconds 

        var st_owl_progressBar,
            st_owl_bar, 
            st_owl_elem, 
            st_owl_isPause, 
            st_owl_tick,
            st_owl_percentTime;
        //Init progressBar where elem is $("#owl-demo")
        function st_owl_progressBar(elem){
          st_owl_elem = elem;
          //build progress bar elements
          st_owl_buildProgressBar();
          //start counting
          st_owl_start();
        }

        //create div#progressBar and div#bar then prepend to $("#owl-demo")
        function st_owl_buildProgressBar(){
          st_owl_progressBar = $("<div>",{
            class:"owl_progressBar"
          });
          st_owl_bar = $("<div>",{
            class:"owl_bar"
          });
          {/literal}
          {if $js_data.progress_bar==1}
            st_owl_progressBar.append(st_owl_bar).prependTo(st_owl_elem);
          {else}
            st_owl_progressBar.append(st_owl_bar).appendTo(st_owl_elem);
          {/if}
          {literal}
        }

        function st_owl_start() {
          //reset timer
          st_owl_percentTime = 0;
          st_owl_isPause = false;
          //run interval every 0.01 second
          st_owl_tick = setInterval(st_owl_interval, 10);
        };

        function st_owl_interval() {
          if(st_owl_isPause === false){
            st_owl_percentTime += 1000 / st_owl_time;
            st_owl_bar.css({
               width: st_owl_percentTime+"%"
             });
            //if st_owl_percentTime is equal or greater than 100
            if(st_owl_percentTime >= 100){
              //slide to next item 
              st_owl_elem.trigger('owl.next')
            }
          }
        }

        //pause while dragging 
        function st_owl_pauseOnDragging(){
          st_owl_isPause = true;
        }

        //moved callback
        function st_owl_moved(){
          //clear interval
          clearTimeout(st_owl_tick);
          //start again
          st_owl_start();
        }

        //uncomment this to make pause on mouseover 
        {/literal}{if $js_data.pause}{literal}
        st_owl_elem.on('mouseover',function(){
          st_owl_isPause = true;
        })
        st_owl_elem.on('mouseout',function(){
          st_owl_isPause = false;
        })
        {/literal}{/if}{/if}{/if}{literal}
    });
{/literal} 
//]]>
</script>