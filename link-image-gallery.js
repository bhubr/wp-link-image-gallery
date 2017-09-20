(function($) {
	$(document).ready(function() {
    var cardTexts = $('.link-card .link-description p');
    var cardBtns = $('.link-card .link-description span');
    console.log(cardTexts, cardBtns);
    cardTexts.bind('mouseover touchstart', function(evt) {
      $(evt.target).parent().addClass('expanded');
    });
    cardTexts.bind('mouseleave touchend', function(evt) {
      $(evt.target).parent().removeClass('expanded');
    });
    cardBtns.bind('click touchstart', function(evt) {
      console.log($(evt.target).parent());
      $(evt.target).parent().toggleClass('expanded');
    });
  });
})('undefined' !== typeof jQuery ? jQuery : Zepto);