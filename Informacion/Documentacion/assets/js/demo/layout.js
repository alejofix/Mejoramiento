  function layoutFormatter (){

    /* Layout Formatter */

    setTimeout(function() {

        var windowH = $(window).height();

        var headerH = $('#page-header').height();

        var sideH = windowH - headerH;

        $('#page-content').css('minHeight',sideH - 113);

        if ($('body').hasClass('boxed-layout')) {

          $('#page-sidebar, #menu-right').height(sideH + 36);

        } else {

          $('#page-sidebar, #menu-right').height(sideH);

        }

    }, 499);

  };

$(window).resize(function(){

  layoutFormatter();

});

function boxedFormatter (){

if ($('body').hasClass('boxed-layout')) {

    var stickyTop = $('.boxed-layout #page-sidebar').offset().top;

    $(window).scroll(function(){

      var windowTop = $(window).scrollTop();

      if (stickyTop < windowTop){
        $('.boxed-layout #page-sidebar').css({ position: 'fixed', top: 10 });
      }
      else {
        $('.boxed-layout #page-sidebar').css({ position: 'absolute', top: 74 });
      }
    });

};

};

$(document).on('click', function(){
  boxedFormatter();
});


$(document).ready(function(){

  boxedFormatter();
  layoutFormatter();

  /* Open responsive nav menu */

$(function() {

  $('#responsive-open-menu').click(function(){
    $('#sidebar-menu').toggle();
  });

});

  /* Sidebar Menu */

$(function() {

  $('#sidebar-menu li').click(function(){

    if($(this).is('.active')) {

      $(this).removeClass('active');

      $('ul', this).slideUp();

    } else {

      $('#sidebar-menu li ul').slideUp();

      $('ul', this).slideDown();

      $('#sidebar-menu li').removeClass('active');

      $(this).addClass('active');

    }

  });

});

  /* Right menu */

$(function() {

    var g10 = new JustGage({
      id: "g10", 
      value: getRandomInt(0, 100), 
      min: 0,
      max: 100,
      title: "NS1",
      titleFontColor: "#ccc",
      label: "oz"
    });

    var g11 = new JustGage({
      id: "g11", 
      value: getRandomInt(0, 100), 
      min: 0,
      max: 100,
      title: "NS2",
      titleFontColor: "#ccc",
      label: "oz"
    });
  
    setInterval(function() {
      g10.refresh(getRandomInt(12, 80));
      g11.refresh(getRandomInt(5, 20));

    }, 2500);

  $('#open-left-menu').on('click', function(){

    $('#menu-right').toggleClass('hidden');

  $(".sprk-1-dash").sparkline( 'html', {
      type: 'line',
      width: '50%',
      height: '65',
      lineColor: '#b2b2b2',
      fillColor: '#ffffff',
      lineWidth: 1,
      spotColor: '#0065ff',
      minSpotColor: '#0065ff',
      maxSpotColor: '#0065ff',
      spotRadius: 4});

  });

});

  /* Sidebar Menu active class */

$(function(){

  var url = window.location;

  $('#sidebar-menu a[href="'+ url +'"]').parent('li').addClass('current-page');

  $('#sidebar-menu a').filter(function() {
    return this.href == url;
  }).parent('li').addClass('current-page').parent('ul').slideDown().parent().addClass('active');

});

  /* Boxed layout */

$(function() {

    $('.boxed-layout-btn').click(function(){

        var boxedCookie = $.cookie('boxedLayout', 'on');

        $('body').addClass('boxed-layout');

        $('.boxed-layout-btn').addClass('hidden');

        $('.fluid-layout-btn').removeClass('hidden');

    });

    $('.fluid-layout-btn').click(function(){

        var boxedCookie = $.cookie('boxedLayout', 'off');

        $('body').removeClass('boxed-layout');

        $('.fluid-layout-btn').addClass('hidden');

        $('.boxed-layout-btn').removeClass('hidden');

        $('#page-sidebar').css('position','fixed');

    });

    var boxedLCookie = $.cookie('boxedLayout');

    if (boxedLCookie == 'on') {

      $('body').addClass('boxed-layout');

      $('.boxed-layout-btn').addClass('hidden');

      $('.fluid-layout-btn').removeClass('hidden');

    }

});

  /* Theme animations */

$(function() {

    $('.enable-animations').click(function(){

        var animCookie = $.cookie('animations', 'enable');

        $('#theme-animations').attr('href','assets/themes/minified/fides/animations.min.css');

        $('.enable-animations').addClass('hidden');

        $('.disable-animations').removeClass('hidden');

    });

    $('.disable-animations').click(function(){

        var animCookie = $.cookie('animations', 'disable');

        $('#theme-animations').attr('href','');

        $('.disable-animations').addClass('hidden');

        $('.enable-animations').removeClass('hidden');

    });

    var animLCookie = $.cookie('animations');

    if (animLCookie == 'disable') {

      $('#theme-animations').attr('href','');

      $('.enable-animations').removeClass('hidden');

      $('.disable-animations').addClass('hidden');

    }

});

  /* Close Sidebar */

$(function(){

  $('#close-sidebar').click(function(){

    $('#page-content-wrapper').animate({marginLeft: 0},300);

    $('body').addClass('close-sidebar');
    var closeSidebarCookie = $.cookie('closesidebar', 'close');
    $(this).addClass('hidden');
    $('#rm-close-sidebar').removeClass('hidden');

  });

  $('#rm-close-sidebar').click(function(){

    $('#page-content-wrapper').animate({marginLeft: 220},300);

    $('body').removeClass('close-sidebar');
    var closeSidebarCookie = $.cookie('closesidebar', 'rm-close');
    $(this).addClass('hidden');
    $('#close-sidebar').removeClass('hidden');

  });

  var sidebarCookie = $.cookie('closesidebar');

  if (sidebarCookie == 'close') {
    $('#close-sidebar').addClass('hidden');
    $('#rm-close-sidebar').removeClass('hidden');
    $('body').addClass('close-sidebar');
  }

});

});
