/*------------------------------------------------------------------------------------
  Get active media query
------------------------------------------------------------------------------------*/
function checkMediaQuery(query){
  var all = $('#screenSize').css('font-family').replace(/['"]+/g, '').split(', ');
  return (all.indexOf(query) > -1);
}



/*------------------------------------------------------------------------------------
  Update user info on resize event
------------------------------------------------------------------------------------*/
function updateOnResize(){
  dlog('Resize updates executed...');
  ux.viewport.width = $(window).width();
  ux.viewport.height = $(window).height();
}



/*------------------------------------------------------------------------------------
  Update user info on scroll even
------------------------------------------------------------------------------------*/
function updateOnScroll(){
  dlog('Scroll updates executed...');
  var treshold = 5;
  ux.scroll.offsetPrevious = ux.scroll.offset;
  ux.scroll.offset = $(window).scrollTop();
  
  // We compare distance traveled with a defined treshold and report scroll direction
  if( ux.scroll.offset - ux.scroll.offsetPrevious > treshold ){
    ux.scroll.direction = 'down';
  } else if( ux.scroll.offsetPrevious - ux.scroll.offset > treshold ){
    ux.scroll.direction = 'up';
  }
  
  ux.viewport.visibleTop = ux.scroll.offset
  ux.viewport.visibleBottom = ux.viewport.height + ux.scroll.offset;
}



/*------------------------------------------------------------------------------------
  Light scroll (which triggers less often and not in real-time)
------------------------------------------------------------------------------------*/
function lightScroll(callback, delay){
  var timer,
      delay = delay || 80,
      scrollCounter = 0;

  $(window).on('scroll', function(e) {
    
    // Count scroll ticks and force callback execution on a specific count
    scrollCounter++;
    if( scrollCounter > 8 ){
      dlog_verbose('Scroll tick');
      callback();
      scrollCounter = 0;
      timer = false;
      return false;
    }

    // If scroll counters are low, proceed with regular Timeout
    clearTimeout(timer);
    timer = setTimeout(function(){
      scrollCounter = 0;
      dlog_verbose('Scroll tick');
      callback();
    }, delay);

  });
};



/*------------------------------------------------------------------------------------
  Light resize (which triggers less often and not in real-time)
------------------------------------------------------------------------------------*/
function lightResize(callback, delay){
  var timer,
      delay = delay || 100;
  $(window).on('resize', function(e) {
    clearTimeout(timer);
    timer = setTimeout(function(){
      dlog_verbose('Resize tick'); // Show only for verbose
      callback();
    }, delay);
  });
};



/*------------------------------------------------------------------------------------
  Load CSS file
------------------------------------------------------------------------------------*/
var loadCSS = function(href) {
  var cssLink = $("<link rel='stylesheet' type='text/css' href='"+href+"'>");
  $('head').append(cssLink); 
};



/*------------------------------------------------------------------------------------
  Check if element is scrolled into view
------------------------------------------------------------------------------------*/
function checkIfVisible(elem, offset) {
  var docViewBottom = $(window).scrollTop() + $(window).height();
  var elemTop = $(elem).offset().top;
  var offset = offset | 0;
  return( (elemTop + offset) <= docViewBottom );
}



// jQuery Dependant...
queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Animate height:auto;
  ------------------------------------------------------------------------------------*/
  (function($){
    $.fn.animateAuto = function(){
      this.each(function(i, el){
        var clone = $(el).clone().css({ 'height':'auto', 'width':$(el).width(), 'position':'absolute', 'left':'-3000px' }).appendTo("body");
        var height = clone.height();
        clone.remove();
        $(el).css({'height':height});
      });
      return this;
    }
  })(window.jQuery || window.Zepto || window.$);



  /*------------------------------------------------------------------------------------
    Run on page load
  ------------------------------------------------------------------------------------*/
  // Get basic user info
  updateOnResize();
  updateOnScroll();


});