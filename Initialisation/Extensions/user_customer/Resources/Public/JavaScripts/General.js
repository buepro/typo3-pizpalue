/**
 * self invoking function:
 *
 *    +function() {}();
 *    or
 *    (function() {})();
 *
 *
 * document ready:
 *
 * $() is shorthand for $( document ).ready()
 *
 * Code included inside $( window ).on( "load", function() { ... }) will run
 * once the entire page (images or iframes), not just the DOM, is ready.
 *
 * @see http://thanpol.as/javascript/you-dont-need-dom-ready
 *
 */
 /**
  * Affix handling
  *
  */
 $(function() {
     // width from side column
     function setWidth() {
         var $sideNav = $('.uc-subnavaffix-wrap');
         if ($sideNav.length) {
             var width = $sideNav.parent().width();
             $sideNav.css('width',width);
         }
     }
     $(window).on('resize',function() {
         setWidth();
     });
     setWidth();

     // scroll correction for breadcrumb
     var offset = $('header').height();
     var $breadCrumb = $('.breadcrumb-section');
     if ($breadCrumb.length > 0) {
         var height = $breadCrumb.height();
         offset += height;
     }
     $('.uc-subnavaffix-wrap').affix({
         offset: {top: offset}
     });
 });
