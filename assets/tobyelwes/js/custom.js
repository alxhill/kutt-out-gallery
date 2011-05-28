var resize = null;

(function($,sr){
 
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;
 
      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null; 
          };
 
          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);
 
          timeout = setTimeout(delayed, threshold || 100); 
      };
  }
	// smartresize 
	jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
 
})(jQuery,'smartresize');

$(function() {
	// variable to store the number of pixels to take off the height of the main content.
	var hh = 320;
	
	function setup() {
		$('#content.home').height($(window).height()-hh);
		$('#home_content').height($(window).height()-(hh-20));
		$('#scroller').simplyScroll({
	        autoMode: 'loop',
	        pauseOnHover: false,
	        speed: 1,
			frameRate: 35,
			startOnLoad: false
		});
				
	}
	
	setup();
	
	$(window).smartresize(setup);
	
});