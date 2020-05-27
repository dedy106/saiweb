$(document).ready(function(){
				//  Add smooth scrolling to all links
				$("a").on('click', function(event) {
			    // Make sure this.hash has a value before overriding default behavior
			    if (this.hash !== "") {
			      // Prevent default anchor click behavior
			      event.preventDefault();

			      // Store hash
			      var hash = this.hash;

			      // Using jQuery's animate() method to add smooth page scroll
			      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
			      $('html, body').animate({
			        scrollTop: $(hash).offset().top-70
			      }, 800, function(){
			   
			        // Add hash (#) to URL when done scrolling (default click behavior)
			        window.location.hash = hash;
			      });
			    } // End if
			  });
			})

			$(window).on('load',function(){
				$('.header-fadeIn').addClass('headerShow');
			});	

			$(window).scroll(function(){
				// Navbar Scroll
				var wScroll = $(this).scrollTop();
				if (wScroll > $('header').offset().top) {
					$('nav').addClass('scrolled');
					// $('.nav-item').removeClass('non-scroll');
					// $('.nav-item').addClass('active-scroll');
				}else{
					$('nav').removeClass('scrolled');
					// $('.nav-item').addClass('non-scroll');
					// $('.nav-item').removeClass('active-scroll');
				}
			});

			function copyToClipboard(element) {
				var $temp = $("<input>");
				$("body").append($temp);
				$temp.val($(element).text()).select();
				document.execCommand("copy");
				$temp.remove();

				alert('Disalin ke papan klip');
			}
			$('#p1').hide();