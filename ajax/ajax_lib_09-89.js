$( function() {
var i = 0;

$('#isloaded').click( function () {
	if (i == 10) {
		var html = $('.header-wrapper h1').html();
		$('.header-wrapper h1').html('<span class="first-letter">C</span>HICKEN <span class="first-letter">T</span>AKEOVER');
		
		var src = $('#logo').attr("src");
		$('#logo').attr("src", "img/alt.png");

		setTimeout( function() {
			$('.header-wrapper h1').html(html);
			$('#logo').attr("src", src);
		}, 2500);
		i = 0;
	}
	else {
		i++;
	}
});
});
