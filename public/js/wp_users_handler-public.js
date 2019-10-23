(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	const pl_basic = {
		init: function () {
			this.prepare();
		},
		prepare: function () {
			$.validator.addMethod(
				"email_regex",
				function(value, element, regexp) {
					var re = new RegExp(regexp);
					return this.optional(element) || re.test(value);
				},
				pl_phrases.email_regex
			);
			$.validator.addMethod(
				"phone_regex",
				function(value, element, regexp) {
					var re = new RegExp(regexp);
					return this.optional(element) || re.test(value);
				},
				pl_phrases.phone_regex
			);
			$.validator.addMethod(
				"password_regex",
				function(value, element, regexp) {
					var re = new RegExp(regexp);
					return this.optional(element) || re.test(value);
				},
				pl_phrases.pass_regex
			);
			$.validator.setDefaults({
				errorPlacement: function(label, element) {
					label.addClass('lg-error');
					if ($('.pmpro_asterisk').length > 0) {
						label.insertAfter(element.parent().find('.pmpro_asterisk'));
					}else{
						label.insertAfter(element);
					}
				},
				highlight: function(element) {
					$(element).addClass('lg-error-input');
				},
				unhighlight: function(element) {
					$(element).removeClass('lg-error-input');
				},
			});

			$.extend($.validator.messages, {
				required: pl_phrases.default,
				email: pl_phrases.email,
				number: pl_phrases.number,
				equalTo: pl_phrases.equalTo,
				maxlength: $.validator.format(pl_phrases.maxlength),
				minlength: $.validator.format(pl_phrases.minlength),
				max: $.validator.format(pl_phrases.max),
				min: $.validator.format(pl_phrases.min)
			});
		}
	};

	$(document).ready(function (e) {
		pl_basic.init();
	});

	$(".custom-file-input").on("change", function() {
		var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});

	$('#UH_login_form').live('submit', function (e) {
		e.preventDefault();
		alert();
	})

})( jQuery );
