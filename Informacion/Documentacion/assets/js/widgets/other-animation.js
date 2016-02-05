$(document).ready(function(){

  /* jQuery Animation */

  $(function(){

    $('.animation-toggle').click(function(){

      var animationEFFECT = $(this).attr('data-animation');
      var animationTARGET = $(this).attr('data-animation-target');

      $(animationTARGET).addClass('animated');
      $(animationTARGET).addClass(animationEFFECT);
      $(animationTARGET).removeClass('hide');
      

      var wait = window.setTimeout( function(){
        $(animationTARGET).removeClass('animated');
        $(animationTARGET).removeClass(animationEFFECT);
        $(animationTARGET).addClass('hide')},

        1300
      );

    });

  });

});