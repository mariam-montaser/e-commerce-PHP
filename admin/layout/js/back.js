$(function () {

	'use strict';

		// dashboard latest users

		$('.toggle-info').click(function () {

			$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

			if ($(this).hasClass('selected')) {

				$(this).html('<i class="fa fa-plus fa-lg"></i>');

			} else {

				$(this).html('<i class="fa fa-minus fa-lg"></i>');
			}

		});

		//trigger the selectBoxIt blugin

		$('select').selectBoxIt({

			autoWidth: false
		});

		//hide placeholder on form focus and show on blur
		$('[placeholder]').focus(function() {

			$(this).attr('data-text', $(this).attr('placeholder'));
			$(this).attr('placeholder', '');

		}).blur(function () {

			$(this).attr('placeholder', $(this).attr('data-text'));

		});

		// validate the form by add asterik

		$('input').each(function() {

			if ($(this).attr('required') === 'required') {

				$(this).after('<span class="asterik">*</span>');
			}

		});

		// show & hide password 

		$('.show-pass').hover(function () {

			$('.password').attr('type', 'text');

		}, function () {

			$('.password').attr('type', 'password');

		});

		// confirm message

		$('.confirm').click(function () {

			return confirm('Are You Sure?');
		});

		// categories view option

		$('.cat h3').click(function () {

			$(this).next('.full-mode').fadeToggle(200);

		});

		$('.option span').click(function () {

			$(this).addClass('active').siblings('span').removeClass('active');

			if ($(this).data('view') === "full") {

				$('.cat .full-mode').fadeIn(200);
			
			} else {

				$('.cat .full-mode').fadeOut(200);
			}

		});

		//show delete in sub categories

		$('.child-link').hover(function () {

			$(this).find('.show-del').fadeIn(200);

		}, function () {

			$(this).find('.show-del').fadeOut(200);

		});
	
});