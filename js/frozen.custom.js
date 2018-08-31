(function($) {
  //Check for click events on the navbar burger icon
  $('.navbar-burger').click(function() {
      //Toggle the 'is-active' class on both the 'navbar-burger' and the 'navbar-menu'
      $(this).toggleClass('is-active');
      $(this).closest('.navbar').find('.navbar-menu').toggleClass('is-active');
  });
})(jQuery);