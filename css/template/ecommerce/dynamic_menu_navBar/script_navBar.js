// $(document).ready(function(){

//   $('#cssmenu_navBar > ul > li:has(ul)').addClass("has-sub");

//   $('#cssmenu_navBar > ul > li > a').click(function() {
//     var checkElement = $(this).next();
    
//     $('#cssmenu_navBar li').removeClass('active');
//     $(this).closest('li').addClass('active'); 
    
    
//     if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
//       $(this).closest('li').removeClass('active');
//       checkElement.slideUp('normal');
//     }
    
//     if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
//       $('#cssmenu_navBar ul ul:visible').slideUp('normal');
//       checkElement.slideDown('normal');
//     }
    
//     if (checkElement.is('ul')) {
//       return false;
//     } else {
//       return true;  
//     }   
//   });

// });

( function( $ ) {
$( document ).ready(function() {
// $('#cssmenu_navBar li.has-sub>a').on('mouseover', function(){
$('#cssmenu_navBar li.has-sub>a').on('click', function(){

    $(this).removeAttr('href');
    var element = $(this).parent('li');
    if (element.hasClass('open')) {
      element.removeClass('open');
      element.find('li').removeClass('open');
      element.find('ul').slideUp(1);
    }
    else {
      element.addClass('open');
      element.children('ul').slideDown(1);
      element.siblings('li').children('ul').slideUp(1);
      element.siblings('li').removeClass('open');
      element.siblings('li').find('li').removeClass('open');
      element.siblings('li').find('ul').slideUp(1);
    }
  });

  $('#cssmenu_navBar>ul>li.has-sub>a').append('<span class="holder"></span>');

  (function getColor() {
    var r, g, b;
    var textColor = $('#cssmenu_navBar').css('color');
    textColor = textColor.slice(4);
    r = textColor.slice(0, textColor.indexOf(','));
    textColor = textColor.slice(textColor.indexOf(' ') + 1);
    g = textColor.slice(0, textColor.indexOf(','));
    textColor = textColor.slice(textColor.indexOf(' ') + 1);
    b = textColor.slice(0, textColor.indexOf(')'));
    var l = rgbToHsl(r, g, b);
    if (l > 0.7) {


      // $('#cssmenu_navBar>ul>li>a').css('text-shadow'," 0 5px 5px #000");
      $('#cssmenu_navBar>ul>li>a>span').css('border-color', '#000');
      

      // $('#cssmenu_navBar>ul>li>a').css('text-shadow', '0 1px 1px rgba(0, 0, 0, .35)');
      // $('#cssmenu_navBar>ul>li>a>span').css('border-color', 'rgba(0, 0, 0, .35)');
    }
    else
    {
      // $('#cssmenu_navBar>ul>li>a').css('text-shadow'," 0 5px 5px #000");
      $('#cssmenu_navBar>ul>li>a>span').css('border-color', '#000');

      // $('#cssmenu_navBar>ul>li>a').css('text-shadow', '0 1px 0 rgba(255, 255, 255, .35)');
      // $('#cssmenu_navBar>ul>li>a>span').css('border-color', 'rgba(255, 255, 255, .35)');
    }
  })();

  function rgbToHsl(r, g, b) {
      r /= 255, g /= 255, b /= 255;
      var max = Math.max(r, g, b), min = Math.min(r, g, b);
      var h, s, l = (max + min) / 2;

      if(max == min){
          h = s = 0;
      }
      else {
          var d = max - min;
          s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
          switch(max){
              case r: h = (g - b) / d + (g < b ? 6 : 0); break;
              case g: h = (b - r) / d + 2; break;
              case b: h = (r - g) / d + 4; break;
          }
          h /= 6;
      }
      return l;
  }
});
} )( jQuery );
