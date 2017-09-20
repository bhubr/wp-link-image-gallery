(function($) {
  console.log('ok');
	$(document).ready(function() {
    var cardTexts = $('.link-card p');
    console.log(cardTexts);
    cardTexts.bind('mouseover', function(evt) {
      $(evt.target).addClass('expanded'); //css('max-height', 'auto');
    });
    cardTexts.bind('mouseleave', function(evt) {
      $(evt.target).removeClass('expanded'); //css('max-height', 'auto');
    });
  });
})(Zepto);