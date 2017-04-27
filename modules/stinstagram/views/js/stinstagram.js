/*! ========================================================================== 
 * pongstagr.am v3.0.4 jQuery Plugin | http://pongstr.github.io/pongstagr.am/ 
 * =========================================================================== 
 * Copyright (c) 2014 Pongstr Ordillo. Licensed under MIT License. 
 * =========================================================================== */

+function ($) {

  var Pongstgrm = function (element, options) {
    this.element  = element;
    this.options  = options;

    return this;
  }

  Pongstgrm.defaults = {

    // USER AUTHENTICATION
    // ===========================
      accessId:     null
    , accessToken:  null

    // DISPLAY OPTIONS
    // ===========================
    , count:       8
    , likes:       true
    , comments:    true
    , username:   false
    , timestamp:   false
    , caption:   false
    , effects:    0
    , show:       "recent"
    , click_action:      0
    //st    
    , grid:       0
    , image_size:       0
    , ins_items_fw       : 8
    , ins_items_xl       : 6
    , ins_items_lg       : 5
    , ins_items_md       : 4
    , ins_items_sm       : 3
    , ins_items_xs       : 2
    , ins_items_xxs      : 1
    , isfooter      : 0
    , show_counts      : 1

    // HTML OPTIONS
    // ===========================
    , preload:          "spinner"
    , button:           "btn btn-success pull-right"
    , buttontext:       "Load more"
    , column:           "col-xs-12 col-sm-3 col-md-3 col-lg-3"
    , likeicon:         " icon-heart-empty "
    , muteicon:         "glyphicon glyphicon-volume-off"
    , videoicon:        " icon-play-circled2 "
    , commenticon:      " icon-comment-empty "
    , externalicon:     " icon-link-ext "
    , picture_size:     64
    , profile_bg_img:   null
    , profile_bg_color: "#d9534f"
  };


  /* HTML TEMPLATES */
  Pongstgrm.prototype.template = {
    loadmore: function (options) {
      var _load  = '<div class="row">';
          _load += '  <button class="'+ options.button +'" data-paginate="'+ options.show +'">';
          _load +=      options.buttontext;
          _load += '  </button>';
          _load += '</div>';

      options.insert !== 'before' ? 
        $(options.target).after (_load) :
        $(options.target).before(_load);

      return
    }
    , profile: function (options) {
        var _profile  = '<div class="ins_profile_img clearfix">';
            _profile += '     <div class="ins_profile_img_left"><a href="https://instagram.com/'+ options.username +'/" rel="nofollow" target="_blank"><img src="'+ options.profile_picture +'" width="'+ options.picture_size +'"  height="'+ options.picture_size +'" alt="'+ options.username +'"></a></div>';
            _profile += '     <div class="ins_profile_img_right"><a href="https://instagram.com/'+ options.username +'/" rel="nofollow" target="_blank" class="ins_profile_name">'+ options.full_name +'</a><a href="https://instagram.com/'+ options.username +'/" rel="nofollow" target="_blank" class="ins_follow_btn">' + ins_follow +'</a></div></div>';
            _profile += ' </div>';

            if(options.show_counts)
            {
              _profile += ' <div class="ins_profile_counts">';
              _profile += '     <div class="ins_pro_c"><span class="ins_pro_c_v">'+ options.media +'</span><span class="ins_pro_c_k">'+ ins_posts +'</span></div>';
              _profile += '     <div class="ins_pro_c"><span class="ins_pro_c_v">'+ options.followed_by +'</span><span class="ins_pro_c_k">'+ ins_followers +'</span></div>';
              _profile += '     <div class="ins_pro_c"><span class="ins_pro_c_v">'+ options.follows +'</span><span class="ins_pro_c_k">'+ ins_following +'</span></span>';
              _profile += ' </div>';
            }

          $(options.target).append(_profile);

        return;
      }
    , thumb: function (options, iteration) {
        var _thumbnail  = '';

            _thumbnail  += options.dflt.grid ?
              '<li class="col-xl-'+ (''+(12/options.dflt.ins_items_xl)).replace('.','-') + ' col-lg-' + (''+(12/options.dflt.ins_items_lg)).replace('.','-') + ' col-md-' + (''+(12/options.dflt.ins_items_md)).replace('.','-') + ' col-sm-' + (''+(12/options.dflt.ins_items_sm)).replace('.','-') + ' col-xs-' + (''+(12/options.dflt.ins_items_xs)).replace('.','-') + ' col-xxs-' + (''+(12/options.dflt.ins_items_xxs)).replace('.','-') + (iteration%options.dflt.ins_items_xl == 1 ? ' first-item-of-large-line ' : '') + (iteration%options.dflt.ins_items_lg == 1 ? ' first-item-of-desktop-line ' : '') + (iteration%options.dflt.ins_items_md == 1 ? ' first-item-of-line ' : '') + (iteration%options.dflt.ins_items_sm == 1 ? ' first-item-of-tablet-line ' : '') + (iteration%options.dflt.ins_items_xs == 1 ? ' first-item-of-mobile-line ' : '') + (iteration%options.dflt.ins_items_xxs == 1 ? ' first-item-of-portrait-line ' : '') + '">' : '';

            _thumbnail  += '<div class="ins_image_box ' + (options.dflt.grid ? ' ins_grid_outer ' : ' ins_slider_outer ') + (options.dflt.effects ? ' scaling ' : '')  + '">';
            options.dflt.click_action ? 
            _thumbnail  += '<a href="'+options.data.link+'" class="ins_external" rel="nofollow" target="_blank"><i class="'+ options.dflt.externalicon +'"></i></a>' : '';
            _thumbnail  += '<a href="'+ (options.dflt.click_action && options.data.type === 'image' ? options.data.standard_resolution : options.data.link) +'" '+ (options.dflt.click_action && options.data.type === 'image' ? ' data-fancybox-group="ins_fancybox_view" ' : '' ) +' class="' + (options.dflt.click_action && options.data.type === 'image' ? ' ins_fancybox ' : '') + ' ins_image_link " title="'+options.data.caption+'" rel="nofollow" target="_blank">';

            options.data.type === 'video' ? 
            _thumbnail += '     <span class="ins_imagetype"><i class="'+ options.dflt.videoicon +'"></i></span>': ''
            _thumbnail += '   <div class="ins_image_info text_table_wrap"><div class="text_table"><div class="text_td clearfix">';

            options.dflt.likes != false || options.dflt.comments != false ?
            _thumbnail += '     <div class="ins_image_info_basic">' : '';
            options.dflt.likes != false ?
            _thumbnail += '       <span class="ins_image_info_likes"><i class="'+ options.dflt.likeicon +'"></i>&nbsp; '+ options.data.likes_count+'</span>' : '';
            options.dflt.comments != false ? 
            _thumbnail += '       <span class="ins_image_info_comments"><i class="'+ options.dflt.commenticon +'"></i>&nbsp; '+ options.data.comments_count+'</span>' : '';
            options.dflt.likes != false || options.dflt.comments != false ?
            _thumbnail += '     </div>' : '';

            options.dflt.username != false ?
            _thumbnail += '     <div class="ins_image_info_username">'+ options.data.username+'</div>' : '';
            options.dflt.timestamp != false ?
            _thumbnail += '     <div class="ins_image_info_timestamp">'+ options.data.timestamp +'</div>' : '';
            options.dflt.caption != false && options.data.caption ?
            _thumbnail += '     <div class="ins_image_info_desc '+ (options.dflt.caption==2 ? 'hidden-xs' : '' ) + '">'+ (options.dflt.ins_lenght_of_caption ? options.data.caption.substr(0, 100) : options.data.caption) +'</div>' : '';
            _thumbnail += '   </div></div></div>'
            // _thumbnail += '   <div class="ins_image_loader" id="'+ options.dflt.show + '-' + options.data.id +'-thmb-loadr">';
            _thumbnail += '   <div class="ins_image_box">';
            _thumbnail += '        <img id="'+ options.dflt.show + '-' + options.data.id +'-thmb" class="ins_image" src="'+ (options.dflt.image_size==2 ? options.data.standard_resolution : (options.dflt.image_size==1 ? options.data.thumbnail : options.data.low_resolution)) +'" alt="'+ options.data.caption +'">';
            _thumbnail += '   </div>';

            options.dflt.grid ?
            _thumbnail += '</a></div></li>' : '</a></div>';

        $(options.target).append(_thumbnail);

      return
    }, simple: function (options) {
      var _thumbnail  = '';
      _thumbnail  += '<li><a href="'+ options.data.link +'" title="'+options.data.caption+'" rel="nofollow" target="_blank">';
      _thumbnail += '    <img src="'+ options.data.thumbnail +'" alt="'+ options.data.caption +'">';
      _thumbnail += '</a></li>';

      $(options.target).append(_thumbnail);
      return;
    }, bsmodal: function (options) {
        
      return;
    }
  }


  Pongstgrm.prototype.preloadMedia = function (option) {
    var $image = $(option.imgid)
      ,  start = 0;

    $image.one('load', function () {
      ++start === $image.length &&
        $(option.loadr).fadeOut()
        $(this).addClass('fade');
    }).each(function () {
      this.complete && $(this).load();
    })

    return;
  }


  Pongstgrm.prototype.videoBtn = function (option, callback) {
    $(option.trigger).on('click', function(e) {
      e.preventDefault(); callback();

      $(option.child, this)
        .toggleClass(option.classes);
    });

    return;
  }

  Pongstgrm.prototype.stream = function () {
    var element = this.element
      , options = this.options
      , apiurl  = 'https://api.instagram.com/v1/users/'
      , rcount  = '?count=' +  options.count + '&access_token=' + options.accessToken;

    function paginate (option) {
      (option.url === undefined || option.url === null) ? 
        $('[data-paginate='+ option.show +']').on('click', function (e) {
            $(this)
              .removeClass()
              .addClass('btn btn-default')
              .attr('disabled','disabled');
          e.preventDefault();
        }) :

        $('[data-paginate='+ option.show +']').on('click', function (e) {
          e.preventDefault();

          ajaxdata({ 
              url: option.url
            , opt: option.opt 
          });

          $(this).unbind(e);
        });

      return;
    }

    function media (data, option) {
      $.each(data, function (a, b) {
        var newtime = new Date(b.created_time * 1000)
          , created = newtime.toDateString()
          , defaults = {
              dflt: option
            , target: element
            , data: {
                  id:             b.id
                , type:           b.type
                , video:          b.videos && b.videos.standard_resolution.url
                , image:          b.images.standard_resolution.url
                , caption:        (b.caption ? b.caption.text : '')
                , username:       b.user.username
                , timestamp:      created
                , thumbnail:      b.images.thumbnail.url
                , standard_resolution:      b.images.standard_resolution.url
                , low_resolution:      b.images.low_resolution.url
                , likes_count:    b.likes.count
                , comments_count: b.comments.count
                , comments_data:  b.comments.data
                , profile_picture:b.user.profile_picture
                , link:           b.link
              }
          };
        if(option.isfooter)
          Pongstgrm.prototype.template.simple (defaults);
        else
          Pongstgrm.prototype.template.thumb (defaults, a+1);

        /*Pongstgrm.prototype.preloadMedia({
            imgid : '#' + option.show + '-' + b.id + '-thmb'
          , loadr : '#' + option.show + '-' + b.id + '-thmb-loadr'
        });*/

        // Pongstgrm.prototype.template.bsmodal (defaults);

      });
      if(!option.grid && !option.isfooter)
        $(element).owlCarousel(option.owl);
      $('.ins_fancybox').fancybox({
        'hideOnContentClick': true,
        'openEffect'  : 'elastic',
        'closeEffect' : 'fade'
      });
    }

    function profile (data, option) {
      Pongstgrm.prototype.template.profile ({
          target:             element
        , bio:                data.bio
        , media:              data.counts.media
        , website:            data.website
        , follows:            data.counts.follows
        , username:           data.username
        , full_name:          data.full_name
        , followed_by:        data.counts.followed_by
        , picture_size:       option.picture_size
        , profile_bg_img:     option.profile_bg_img
        , profile_picture:    data.profile_picture
        , profile_bg_color:   option.profile_bg_color
        , show_counts:   option.show_counts
      });
      return;
    }

    function ajaxdata (option) {
      $.ajax({
          url      : option.url
        , cache    : true
        , method   : 'GET'
        , dataType : 'jsonp' 
        , success  : function(data){
            $(element). removeClass('ins_connecting');
            if(data.meta.code==200)
            {
              if(option.opt.show !== 'profile')
              {
                if(data.data.length==0)
                  $(element).parent().find('.warning').removeClass('hidden');
                else
                  media   (data.data, option.opt)
              }
              else
                profile (data.data, option.opt);
            }

            /*option.opt.show !== 'profile' &&
              paginate ({ 
                  show: option.opt.show
                , url:  data.pagination.next_url
                , opt: option.opt
              });*/
          }
      });

      return;
    }

    switch (options.show) {
      case 'liked':
        ajaxdata({
            url : apiurl + 'self/media/liked' + rcount
          , opt : options
        });
      break

      case 'feed':
        ajaxdata({
            url: apiurl + 'self/feed' + rcount
          , opt: options
        });
      break

      case 'profile':
        ajaxdata({
            // url: apiurl + options.accessId + '?access_token=' + options.accessToken
            url: apiurl + 'self?access_token=' + options.accessToken
          , opt: options
        });
      break

      case 'recent':
        ajaxdata({
            // url: apiurl + options.accessId + '/media/recent' + rcount
            url: apiurl + 'self/media/recent' + rcount
          , opt: options
        });
      break

      default:
        ajaxdata({
            url: 'https://api.instagram.com/v1/tags/' + options.show + '/media/recent' + rcount
          , opt: options
        });
    }

    return;
  }


  Pongstgrm.prototype.create = function () {
    var element = this.element
      , options = this.options;

    $(element)
      .attr('data-type', options.show)
      .addClass('pongstagrm');


/*    options.show !== 'profile' &&
      Pongstgrm.prototype.template.loadmore({
          show:       options.show
        , target:     element
        , button:     options.button
        , buttontext: options.buttontext
      })*/

    this.stream();

    return;
  }


  Pongstgrm.prototype.start = function () {
    var option = this.options;
    if (option.accessId !== null || option.accessToken !== null) {
      this.create(); return;
    }
  }

  // PONGSTAGR.AM PLUGIN DEFINITON
  // =============================
  $.fn.pongstgrm = function (option) {
    var options  = $.extend({}, Pongstgrm.defaults, option);

    return this.each(function () {
      var media = new Pongstgrm($(this)[0], options);
          media.start();
    });
  }


  // PONGSTAGR.AM DEFAULT OPTIONS
  // =============================  
  $.fn.pongstgrm.defaults = Pongstgrm.options;

}(window.jQuery);