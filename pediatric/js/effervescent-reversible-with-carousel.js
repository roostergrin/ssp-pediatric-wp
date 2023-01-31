$(document).ready(function() {
    if ( $(window).width() < 670 ) {
      startCarousel();
    }
  
  $(window).resize(function() {
      if ( $(window).width() < 670 ) {
        startCarousel();
      } else {
        stopCarousel();
      }
  });
  
  function startCarousel(){
    $(".slides").owlCarousel({
       slideSpeed : 500,
       paginationSpeed : 400,
       margin: 0,
       autoplay:true,
       items : 1,
       loop: true,
    });
  }
  function stopCarousel() {
    var owl = $('.slides');
    owl.trigger('destroy.owl.carousel');
  }
});