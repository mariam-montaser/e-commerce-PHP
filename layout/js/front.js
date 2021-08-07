$(function () {

	'use strict';

		// swtich between login & signup

		$('.login-page h1 span').click(function () {

			$(this).addClass('selected').siblings().removeClass('selected');

			$('.login-page form').hide();
			
			$('.' + $(this).data('class')).show();

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

		//live preview for ads

		$('.live').keyup(function () {

			$('.' + $(this).data('class')).text($(this).val());

		});
	
});