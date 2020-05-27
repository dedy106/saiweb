// $(function(){
			//   $("#js-slide").slider();
			// });

			$(function(){
			  $("#js-slide").slider({

			    // animation speed
			    speed: 1000,

			    // animation delay
			    delay: 3000, 

			    // easing function
			    easing: 'ease', 

			    // shows pagination controls
			    pager: true,

			    // shows navigation arrows
			    arrow: false,

			    // autoplay
			    autoPlay: true,

			    // initial index
			    index: 0,

			    // breakpoint in px
			    spWidth: 768
			    
			  });
			});

			// const carouselSlide = document.querySelector('.carousel-slide');
			// const carouselImages = document.querySelectorAll('.carousel-slide img');
			// // Buttons
			// const prevBtn = document.querySelector('#prevBtn');
			// const nextBtn = document.querySelector('#nextBtn');

			// // Counter
			// let counter = 0;
			// const size = carouselImages[0].clientWidth;

			// // carouselSlide.style.transform = 'translateX('+ (-size*counter)+'px)';

			// // Button
			// nextBtn.addEventListener('click',()=>{
			// 	if (counter >= carouselImages.length-1) return;
			// 	carouselSlide.style.transition = "transform 0.4s ease-in-out";
			// 	carouselImages[counter].style.opacity = '1';
			// 	carouselImages[counter].style.height = '400';
			// 	counter++;
			// 	console.log(counter);
			// 	carouselSlide.style.transform = 'translate('+(-size*counter)+'px)';
			// });

			// prevBtn.addEventListener('click',()=>{
			// 	if (counter <= 0) return;
			// 	carouselSlide.style.transition = "transform 0.4s ease-in-out";
			// 	carouselImages[counter].style.opacity = '1';
			// 	carouselImages[counter].style.height = '400';
			// 	counter--;
			// 	console.log(counter);
			// 	carouselSlide.style.transform = 'translate('+(-size*counter)+'px)';
			// });

			// carouselSlide.addEventListener('transitionend',()=>{
			// 	if (carouselImages[counter].id === 'lastClone') {
			// 		carouselSlide.style.transition = "none";
			// 		counter = carouselImages.length -  2;
			// 		carouselSlide.style.transform = 'translate('+(-size*counter)+'px)';
			// 		// console.log('none');
			// 	}
			// 	if (carouselImages[counter].id === 'firstClone') {
			// 		carouselSlide.style.transition = "none";
			// 		counter = carouselImages.length -  counter;
			// 		carouselSlide.style.transform = 'translate('+(-size*counter)+'px)';
			// 		// console.log('none');
			// 	}
			// });

			$(document).ready(function(){

				var w = window.outerWidth;

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
			        scrollTop: $(hash).offset().top-50
			      }, 800, function(){
			   
			        // Add hash (#) to URL when done scrolling (default click behavior)
			        window.location.hash = hash;
			      });
			    } // End if
			  });
			})

			$(window).on('load',function(){
				$('.myslide').addClass('show');
				$('.header-fadeIn').addClass('show');
			});

			// $(window).on('load', function() {
			 //    if (w<=480) {
				// 	$('.img-banner').removeClass('content-right');
				// 	$('.img-banner').css({
				// 		'margin-top' : '40px'
				// 	});
				// }else{
				// 	$('.img-banner').addClass('content-right');
				// 	$('.img-banner').css({
				// 		'margin-top' : '0px'
				// 	});
				// }
			//     var Body = $('body');
			//     Body.addClass('preloader-site');
			//     var w = window.outerWidth;
			// });

			// $(document).ready(function() {
			//     $('.preloader-wrapper').fadeOut();
			//     $('body').removeClass('preloader-site');
			// });

			// $(document).ready(function($) {
			//     $('body').addClass('preloader-site');
			// });
			// $(window).on('load',function() {
			//     $('.preloader-wrapper').fadeOut();
			//     $('body').removeClass('preloader-site');
			// });

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

			// Parallax
				var w = window.outerWidth;

				if (w >= 1 && w<480) {
					$('.header-title').css({
						'transform' : 'translate(0px,-'+wScroll/2+'%)'
					});
					$('.header-content').css({
						'transform' : 'translate(0px,-'+wScroll/1.1+'%)'
					});
					$('.btn-saku').css({
						'transform' : 'translate(0px,-'+wScroll/1+'%)'
					});
					$('.header-img').css({
						'transform' : 'translate(0px,-'+wScroll/6+'%)'
					});

					// $('.header-title').css({
					// 	'transform' : 'translate(0px,-'+wScroll/1.5+'%)'
					// });
					// $('.header-content').css({
					// 	'transform' : 'translate(0px,-'+wScroll/2.25+'%)'
					// });
					// $('.btn-saku').css({
					// 	'transform' : 'translate(0px,-'+wScroll/2+'%)'
					// });
					// $('.header-img').css({
					// 	'transform' : 'translate(0px,-'+wScroll/4+'%)'
					// });

					// Intro
					if (wScroll>$('#intro').offset().top-400&&wScroll<$('#banner').offset().top-250) {
						$('.intro-easeUp').each(function(i){
							setTimeout(function(){
								$('.intro-easeUp').eq(i).addClass('show');
							}, 300 * (i+1));
						});
					}
					if (wScroll>$('#banner').offset().top-100||wScroll<$('#intro').offset().top-400) {
						$('.intro-easeUp').each(function(){
							$('.intro-easeUp').removeClass('show');
						});
					}

					// Banner
					if (wScroll>$('#banner').offset().top - 150&&wScroll<$('#fitur').offset().top-400){
						$('.banner-slideRight').addClass('show');
						$('.banner-slideLeft').addClass('show');
					}

					if (wScroll>$('#fitur').offset().top-100||wScroll<$('#banner').offset().top-300) {
						$('.banner-slideRight').removeClass('show');
						$('.banner-slideLeft').removeClass('show');
					}

					// Fitur
					
					if (wScroll>$('#fitur').offset().top-300&&wScroll<$('#sekilas').offset().top-100) {
						$('.fitur-easeUp').each(function(i){
							setTimeout(function(){
								$('.fitur-easeUp').eq(i).addClass('show');
							}, 400 * (i+1));
						});
					}

					if (wScroll<$('#fitur').offset().top-400||wScroll>$('#sekilas').offset().top-50) {
						$('.fitur-easeUp').removeClass('show');
					}

					// Sekilas
					if (wScroll>$('#sekilas').offset().top-100&&wScroll<$('#sekilas').offset().top){
						$('.sekilas-fadeIn').addClass('show');
					}

					if (wScroll<$('#sekilas').offset().top-400||wScroll>$('#perusahaan').offset().top) {
						$('.sekilas-fadeIn').removeClass('show');
					}

					// Perusahaan
					if (wScroll>$('#perusahaan').offset().top - 200){
						$('.perusahaan-slideRight').addClass('show');
						$('.perusahaan-slideLeft').addClass('show');
					}

					if (wScroll<$('#perusahaan').offset().top - 500){
						$('.perusahaan-slideRight').removeClass('show');
					}
				}
				else{
					// $('.header-title').css({
					// 	'transform' : 'translate(0px,'+wScroll/4+'%)'
					// });
					// $('.header-content').css({
					// 	'transform' : 'translate(0px,'+wScroll/1.25+'%)'
					// });
					// $('.btn-saku').css({
					// 	'transform' : 'translate(0px,'+wScroll/2+'%)'
					// });
					// $('.header-img').css({
					// 	'transform' : 'translate(0px,'+wScroll/16+'%)'
					// });

					// Parallax to Bottom

					// $('.header-title').css({
					// 	'transform' : 'translate(0px,'+wScroll/.85+'%)'
					// });
					// $('.header-content').css({
					// 	'transform' : 'translate(0px,'+wScroll/.3+'%)'
					// });
					// $('.btn-saku').css({
					// 	'transform' : 'translate(0px,'+wScroll/.85+'%)'
					// });
					// $('.header-img').css({
					// 	'transform' : 'translate(0px,'+wScroll/14+'%)'
					// });

					// Parallax to Top

					// $('.header-title').css({
					// 	'transform' : 'translate(0px,-'+wScroll/6+'%)'
					// });
					// $('.header-content').css({
					// 	'transform' : 'translate(0px,-'+wScroll/1.5+'%)'
					// });
					// $('.btn-saku').css({
					// 	'transform' : 'translate(0px,-'+wScroll/2+'%)'
					// });
					// $('.header-img').css({
					// 	'transform' : 'translate(0px,-'+wScroll/12+'%)'
					// });

					// $('.header-title').css({
					// 	'transform' : 'translate(0px,'+wScroll/1.1+'%)'
					// });
					// $('.header-content').css({
					// 	'transform' : 'translate(0px,'+wScroll/.4+'%)'
					// });
					// $('.btn-saku').css({
					// 	'transform' : 'translate(0px,'+wScroll/.95+'%)'
					// });
					// $('.header-img').css({
					// 	'transform' : 'translate(0px,'+wScroll/14+'%)'
					// });

					// Header
					// $('header').css({
					// 	'transform' : 'translate(-'+wScroll/2+'%, 0px)'
					// });

					// $('.header-title').css({
					// 	'transform' : 'translate(0px,-'+wScroll/6+'%)'
					// });
					// $('.header-content').css({
					// 	'transform' : 'translate(0px,-'+wScroll/1.5+'%)'
					// });
					// $('.btn-saku').css({
					// 	'transform' : 'translate(0px,-'+wScroll/2+'%)'
					// });
					// $('.header-img').css({
					// 	'transform' : 'translate(0px,-'+wScroll/12+'%)'
					// });


					// Intro
					if (wScroll>$('#intro').offset().top - 500&&wScroll<$('#banner').offset().top-150) {
						$('.intro-easeUp').each(function(i){
							setTimeout(function(){
								$('.intro-easeUp').eq(i).addClass('show');
							}, 300 * (i+1));
						});
					}

					if (wScroll>$('#banner').offset().top-100||wScroll<$('#intro').offset().top-500) {
						$('.intro-easeUp').each(function(){
							$('.intro-easeUp').removeClass('show');
						});
					}

					// Banner

					if (wScroll>$('#banner').offset().top - 400&&wScroll<$('#sekilas').offset().top-100){
						$('.banner-slideRight').addClass('show');
						$('.banner-slideLeft').addClass('show');
					}

					if (wScroll>$('#sekilas').offset().top-100||wScroll<$('#banner').offset().top-300) {
						$('.banner-slideRight').removeClass('show');
						$('.banner-slideLeft').removeClass('show');
					}

					// Fitur
					
					if (wScroll>$('#fitur').offset().top-400&&wScroll<$('#sekilas').offset().top-300) {
						$('.fitur-easeUp').each(function(i){
							setTimeout(function(){
								$('.fitur-easeUp').eq(i).addClass('show');
							}, 200 * (i+1));
						});
					}

					if (wScroll<$('#fitur').offset().top-400||wScroll>$('#sekilas').offset().top-100) {
						$('.fitur-easeUp').removeClass('show');
					}

					// Sekilas
					if (wScroll>$('#sekilas').offset().top-200&&wScroll<$('#perusahaan').offset().top-100){
						$('.sekilas-fadeIn').addClass('show');
					}

					if (wScroll<$('#sekilas').offset().top-400||wScroll>$('#perusahaan').offset().top-100) {
						$('.sekilas-fadeIn').removeClass('show');
					}

					// Perusahaan
					if (wScroll>$('#perusahaan').offset().top - 200){
						$('.perusahaan-slideRight').addClass('show');
						$('.perusahaan-slideLeft').addClass('show');
					}

					if (wScroll<$('#perusahaan').offset().top - 400){
						$('.perusahaan-slideRight').removeClass('show');
						$('.perusahaan-slideLeft').removeClass('show');
					}

				}


				// Footer
				// if (wScroll>$('#footer').offset().top - 500){
				// 	$('.footer-easeDown').addClass('show');
				// }
				// if (wScroll<$('#footer').offset().top-500){
				// 	$('.footer-easeDown').removeClass('show');
				// }

			
			});

			

			// $(window).resize(function(){
			// 	var w = window.outerWidth;
			//     if (w<=480) {
			// 		$('.img-banner').removeClass('content-right');
			// 		$('.img-banner').css({
			// 			'margin-top' : '40px'
			// 		});
			// 	}else{
			// 		$('.img-banner').addClass('content-right');
			// 		$('.img-banner').css({
			// 			'margin-top' : '0px'
			// 		});
			// 	}
			//     $('body').removeClass('preloader-site');
			// 		
			// });

			

			