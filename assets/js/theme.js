(function($){
	$(function() {
		FastClick.attach(document.body)

		var $window = $(window)
		var $html = $('html')
		var $scrolled = $('.scrolled-back')

		$('article iframe').wrap('<div class="video-container"></div>')

		$('.works a').on('touchstart mouseover', function(){
			$('body').addClass('stealth')
			$('#'+$(this).attr('data-id')).css({opacity:1})
		}).on('touchend mouseout', function(){
			$('body').removeClass('stealth')
			$('#'+$(this).attr('data-id')).css({opacity:0})
		})

		var scrollBack = function(){
			if($window.width() > 420){
				$scrolled.css({ top: -(($scrolled[0].scrollHeight - $window.height()) * $window.scrollTop() / ($html[0].scrollHeight - $window.height())) })
			}
		}

		if($scrolled.length){
			$window.scroll(scrollBack)
			$window.resize(scrollBack)
		}
	})
})(jQuery)