(function($){
	$(function() {
		FastClick.attach(document.body)

		var $window = $(window)
		var $html = $('html')
		var $body = $('body')
		var winW = $window.width()
		var winH = $window.height()
		var winScrollTop = $window.scrollTop()

		var app = {

			// set global actions
			init: function init(){
				$('.text-content iframe').wrap('<div class="video-container"></div>')

				app.setButtons()
				app.setAnimations()

				$window.scroll(app.onScroll)
				$window.resize(app.onResize)
				$window.trigger('resize')
			},

			// animate.css like
			setAnimations: function setAnimations() {
			  if (Modernizr.touch) return $('.animate').addClass('animated')

		  	var win_height_padded = $window.height() * 1.2
			  var revealOnScroll = function() {
			    var scrolled = $window.scrollTop()

			    $('.animate:not(.animated)').each(function (i) {
			      var $this = $(this)

			      if (scrolled + win_height_padded > $this.offset().top) {
			      	setTimeout(function(){
			      		$this.addClass('animated')
			      	}, ((i+1) * 200) )
			      }
			    })
			  }
			  revealOnScroll()
			  $window.on('scroll', revealOnScroll)
			},

			// burger menu btn
			setButtons: function setButtons() {
				$('.toggle-menu').click(function(){
					$body.toggleClass('open-menu')
					return false
				})
			},

			// on page resize
			onScroll: function onScroll() {
				winScrollTop = $window.scrollTop()
			},

			// on window resize
			onResize: function onResize() {
				winW = $window.width()
				winH = $window.height()
				app.onScroll()
			},

		}

		app.init()
	})
})(jQuery)
