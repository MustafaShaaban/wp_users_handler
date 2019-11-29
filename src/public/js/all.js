(function ($) {
    $(document).ready(function () {

        $.fn.serializeObject = function () {
            "use strict";
            var a = {}, b = function (b, c) {
                var d = a[c.name];
                "undefined" != typeof d && d !== null ? $.isArray(d) ? d.push(c.value) : a[c.name] = [d, c.value] : a[c.name] = c.value
            };
            return $.each(this.serializeArray(), b), a
        };

        window.pl_createLoader = function (wrapper, type = true) {
            if (type === true) {
                $('.lg-loader').remove();
                wrapper.append('<div class="cssload-container lg-loader"><div class="cssload-loading"><i></i><i></i><i></i><i></i></div></div>');
                $(".lg-loader").animate({
                    opacity: 1
                }, 250);
            } else {
                $('.lg-loader').fadeOut(250, function () {
                    $('.lg-loader').remove();
                });
            }
        };

        window.pl_createNotice = function (wrapper, msg, flag = 'success', type = true) {

            if (type === true) {
                $('.message').remove();
                wrapper.append('<p class="message lg-messages message-' + flag + '">' + msg + '</p>');
                $(".message").animate({
                    opacity: 1
                }, 200);
            } else {
                $('.message').fadeOut(200, function () {
                    $('.message').remove();
                });
            }
        };

        window.pl_emptyForm = function (form) {
            form.find(':input:not([type=hidden], [type=checkbox])').val('');
            form.find('textarea').val('');
        };

    });
})(jQuery);
(function( $ ) {
	'use strict';

	const public_ajaxRequests = {};

	const pl_basic = {
		init: function () {
			this.prepare();
			pl_login.init();
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
					label.addClass('pl-error');
					label.insertAfter(element);
				},
				highlight: function(element) {
					$(element).addClass('pl-error-input');
				},
				unhighlight: function(element) {
					$(element).removeClass('pl-error-input');
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

	const pl_notices = {
		init: function () {
			this.forms();
		},
		forms: function (msg, $wrapper, type = 'error') {
			let html_success = '<div class="pl-form-notice"><div class="success"><p>' + msg + '</p></div></div>',
				html_false = '<div class="pl-form-notice"><div class="error"><p>' + msg + '</p></div></div>';
			$wrapper.find('.pl-form-notice').remove();
			if (type === 'success') {
				$wrapper.prepend(html_success);
			} else if (type === 'error') {
				$wrapper.prepend(html_false);
			}
		},
	};

	const pl_login = {
		init: function () {
			this.login();
		},

		login: function () {
			let that = this,
				$form = $('#UH_login_form');

			if ($form.length > 0) {
				this.initValidation($form);
			}

			$form.live('submit', function (e) {
				e.preventDefault();
				let $this = $(e.currentTarget),
					formData = $this.serializeObject();

				if (typeof public_ajaxRequests.login !== 'undefined') {
					public_ajaxRequests.login.abort();
				}

				public_ajaxRequests.login = $.ajax({
					url: pl_globals.ajaxUrl,
					type: 'POST',
					data: {
						action: 'UH_login_ajax',
						data: formData
					},
					beforeSend: function () {
						$('input').prop('disabled', true);
					},
					success: function (res) {
						$('input').prop('disabled', false);
						if (res.success) {
							pl_notices.forms(res.msg, $this, 'success');
							window.location.href = res.redirect_url;
						} else {
							pl_notices.forms(res.msg, $this);
						}
					},
					error: function (xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText;
						if (xhr.statusText !== 'abort') {
							console.error('Error - ' + errorMessage);
						}
					}
				});
			})
		},

		initValidation: function(el) {
			el.validate({
				normalizer: function (value) {
					return $.trim(value);
				},
				rules: {
					user_login: "required",
					user_password: "required",
				}
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

})( jQuery );

//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImZ1bmN0aW9ucy5qcyIsIndwX3VzZXJzX2hhbmRsZXItcHVibGljLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQy9DQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJhbGwuanMiLCJzb3VyY2VzQ29udGVudCI6WyIoZnVuY3Rpb24gKCQpIHtcclxuICAgICQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgJC5mbi5zZXJpYWxpemVPYmplY3QgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIFwidXNlIHN0cmljdFwiO1xyXG4gICAgICAgICAgICB2YXIgYSA9IHt9LCBiID0gZnVuY3Rpb24gKGIsIGMpIHtcclxuICAgICAgICAgICAgICAgIHZhciBkID0gYVtjLm5hbWVdO1xyXG4gICAgICAgICAgICAgICAgXCJ1bmRlZmluZWRcIiAhPSB0eXBlb2YgZCAmJiBkICE9PSBudWxsID8gJC5pc0FycmF5KGQpID8gZC5wdXNoKGMudmFsdWUpIDogYVtjLm5hbWVdID0gW2QsIGMudmFsdWVdIDogYVtjLm5hbWVdID0gYy52YWx1ZVxyXG4gICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICByZXR1cm4gJC5lYWNoKHRoaXMuc2VyaWFsaXplQXJyYXkoKSwgYiksIGFcclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICB3aW5kb3cucGxfY3JlYXRlTG9hZGVyID0gZnVuY3Rpb24gKHdyYXBwZXIsIHR5cGUgPSB0cnVlKSB7XHJcbiAgICAgICAgICAgIGlmICh0eXBlID09PSB0cnVlKSB7XHJcbiAgICAgICAgICAgICAgICAkKCcubGctbG9hZGVyJykucmVtb3ZlKCk7XHJcbiAgICAgICAgICAgICAgICB3cmFwcGVyLmFwcGVuZCgnPGRpdiBjbGFzcz1cImNzc2xvYWQtY29udGFpbmVyIGxnLWxvYWRlclwiPjxkaXYgY2xhc3M9XCJjc3Nsb2FkLWxvYWRpbmdcIj48aT48L2k+PGk+PC9pPjxpPjwvaT48aT48L2k+PC9kaXY+PC9kaXY+Jyk7XHJcbiAgICAgICAgICAgICAgICAkKFwiLmxnLWxvYWRlclwiKS5hbmltYXRlKHtcclxuICAgICAgICAgICAgICAgICAgICBvcGFjaXR5OiAxXHJcbiAgICAgICAgICAgICAgICB9LCAyNTApO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgJCgnLmxnLWxvYWRlcicpLmZhZGVPdXQoMjUwLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgJCgnLmxnLWxvYWRlcicpLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICB3aW5kb3cucGxfY3JlYXRlTm90aWNlID0gZnVuY3Rpb24gKHdyYXBwZXIsIG1zZywgZmxhZyA9ICdzdWNjZXNzJywgdHlwZSA9IHRydWUpIHtcclxuXHJcbiAgICAgICAgICAgIGlmICh0eXBlID09PSB0cnVlKSB7XHJcbiAgICAgICAgICAgICAgICAkKCcubWVzc2FnZScpLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgd3JhcHBlci5hcHBlbmQoJzxwIGNsYXNzPVwibWVzc2FnZSBsZy1tZXNzYWdlcyBtZXNzYWdlLScgKyBmbGFnICsgJ1wiPicgKyBtc2cgKyAnPC9wPicpO1xyXG4gICAgICAgICAgICAgICAgJChcIi5tZXNzYWdlXCIpLmFuaW1hdGUoe1xyXG4gICAgICAgICAgICAgICAgICAgIG9wYWNpdHk6IDFcclxuICAgICAgICAgICAgICAgIH0sIDIwMCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAkKCcubWVzc2FnZScpLmZhZGVPdXQoMjAwLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgJCgnLm1lc3NhZ2UnKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgd2luZG93LnBsX2VtcHR5Rm9ybSA9IGZ1bmN0aW9uIChmb3JtKSB7XHJcbiAgICAgICAgICAgIGZvcm0uZmluZCgnOmlucHV0Om5vdChbdHlwZT1oaWRkZW5dLCBbdHlwZT1jaGVja2JveF0pJykudmFsKCcnKTtcclxuICAgICAgICAgICAgZm9ybS5maW5kKCd0ZXh0YXJlYScpLnZhbCgnJyk7XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICB9KTtcclxufSkoalF1ZXJ5KTsiLCIoZnVuY3Rpb24oICQgKSB7XG5cdCd1c2Ugc3RyaWN0JztcblxuXHRjb25zdCBwdWJsaWNfYWpheFJlcXVlc3RzID0ge307XG5cblx0Y29uc3QgcGxfYmFzaWMgPSB7XG5cdFx0aW5pdDogZnVuY3Rpb24gKCkge1xuXHRcdFx0dGhpcy5wcmVwYXJlKCk7XG5cdFx0XHRwbF9sb2dpbi5pbml0KCk7XG5cdFx0fSxcblx0XHRwcmVwYXJlOiBmdW5jdGlvbiAoKSB7XG5cdFx0XHQkLnZhbGlkYXRvci5hZGRNZXRob2QoXG5cdFx0XHRcdFwiZW1haWxfcmVnZXhcIixcblx0XHRcdFx0ZnVuY3Rpb24odmFsdWUsIGVsZW1lbnQsIHJlZ2V4cCkge1xuXHRcdFx0XHRcdHZhciByZSA9IG5ldyBSZWdFeHAocmVnZXhwKTtcblx0XHRcdFx0XHRyZXR1cm4gdGhpcy5vcHRpb25hbChlbGVtZW50KSB8fCByZS50ZXN0KHZhbHVlKTtcblx0XHRcdFx0fSxcblx0XHRcdFx0cGxfcGhyYXNlcy5lbWFpbF9yZWdleFxuXHRcdFx0KTtcblx0XHRcdCQudmFsaWRhdG9yLmFkZE1ldGhvZChcblx0XHRcdFx0XCJwaG9uZV9yZWdleFwiLFxuXHRcdFx0XHRmdW5jdGlvbih2YWx1ZSwgZWxlbWVudCwgcmVnZXhwKSB7XG5cdFx0XHRcdFx0dmFyIHJlID0gbmV3IFJlZ0V4cChyZWdleHApO1xuXHRcdFx0XHRcdHJldHVybiB0aGlzLm9wdGlvbmFsKGVsZW1lbnQpIHx8IHJlLnRlc3QodmFsdWUpO1xuXHRcdFx0XHR9LFxuXHRcdFx0XHRwbF9waHJhc2VzLnBob25lX3JlZ2V4XG5cdFx0XHQpO1xuXHRcdFx0JC52YWxpZGF0b3IuYWRkTWV0aG9kKFxuXHRcdFx0XHRcInBhc3N3b3JkX3JlZ2V4XCIsXG5cdFx0XHRcdGZ1bmN0aW9uKHZhbHVlLCBlbGVtZW50LCByZWdleHApIHtcblx0XHRcdFx0XHR2YXIgcmUgPSBuZXcgUmVnRXhwKHJlZ2V4cCk7XG5cdFx0XHRcdFx0cmV0dXJuIHRoaXMub3B0aW9uYWwoZWxlbWVudCkgfHwgcmUudGVzdCh2YWx1ZSk7XG5cdFx0XHRcdH0sXG5cdFx0XHRcdHBsX3BocmFzZXMucGFzc19yZWdleFxuXHRcdFx0KTtcblx0XHRcdCQudmFsaWRhdG9yLnNldERlZmF1bHRzKHtcblx0XHRcdFx0ZXJyb3JQbGFjZW1lbnQ6IGZ1bmN0aW9uKGxhYmVsLCBlbGVtZW50KSB7XG5cdFx0XHRcdFx0bGFiZWwuYWRkQ2xhc3MoJ3BsLWVycm9yJyk7XG5cdFx0XHRcdFx0bGFiZWwuaW5zZXJ0QWZ0ZXIoZWxlbWVudCk7XG5cdFx0XHRcdH0sXG5cdFx0XHRcdGhpZ2hsaWdodDogZnVuY3Rpb24oZWxlbWVudCkge1xuXHRcdFx0XHRcdCQoZWxlbWVudCkuYWRkQ2xhc3MoJ3BsLWVycm9yLWlucHV0Jyk7XG5cdFx0XHRcdH0sXG5cdFx0XHRcdHVuaGlnaGxpZ2h0OiBmdW5jdGlvbihlbGVtZW50KSB7XG5cdFx0XHRcdFx0JChlbGVtZW50KS5yZW1vdmVDbGFzcygncGwtZXJyb3ItaW5wdXQnKTtcblx0XHRcdFx0fSxcblx0XHRcdH0pO1xuXG5cdFx0XHQkLmV4dGVuZCgkLnZhbGlkYXRvci5tZXNzYWdlcywge1xuXHRcdFx0XHRyZXF1aXJlZDogcGxfcGhyYXNlcy5kZWZhdWx0LFxuXHRcdFx0XHRlbWFpbDogcGxfcGhyYXNlcy5lbWFpbCxcblx0XHRcdFx0bnVtYmVyOiBwbF9waHJhc2VzLm51bWJlcixcblx0XHRcdFx0ZXF1YWxUbzogcGxfcGhyYXNlcy5lcXVhbFRvLFxuXHRcdFx0XHRtYXhsZW5ndGg6ICQudmFsaWRhdG9yLmZvcm1hdChwbF9waHJhc2VzLm1heGxlbmd0aCksXG5cdFx0XHRcdG1pbmxlbmd0aDogJC52YWxpZGF0b3IuZm9ybWF0KHBsX3BocmFzZXMubWlubGVuZ3RoKSxcblx0XHRcdFx0bWF4OiAkLnZhbGlkYXRvci5mb3JtYXQocGxfcGhyYXNlcy5tYXgpLFxuXHRcdFx0XHRtaW46ICQudmFsaWRhdG9yLmZvcm1hdChwbF9waHJhc2VzLm1pbilcblx0XHRcdH0pO1xuXHRcdH1cblx0fTtcblxuXHRjb25zdCBwbF9ub3RpY2VzID0ge1xuXHRcdGluaXQ6IGZ1bmN0aW9uICgpIHtcblx0XHRcdHRoaXMuZm9ybXMoKTtcblx0XHR9LFxuXHRcdGZvcm1zOiBmdW5jdGlvbiAobXNnLCAkd3JhcHBlciwgdHlwZSA9ICdlcnJvcicpIHtcblx0XHRcdGxldCBodG1sX3N1Y2Nlc3MgPSAnPGRpdiBjbGFzcz1cInBsLWZvcm0tbm90aWNlXCI+PGRpdiBjbGFzcz1cInN1Y2Nlc3NcIj48cD4nICsgbXNnICsgJzwvcD48L2Rpdj48L2Rpdj4nLFxuXHRcdFx0XHRodG1sX2ZhbHNlID0gJzxkaXYgY2xhc3M9XCJwbC1mb3JtLW5vdGljZVwiPjxkaXYgY2xhc3M9XCJlcnJvclwiPjxwPicgKyBtc2cgKyAnPC9wPjwvZGl2PjwvZGl2Pic7XG5cdFx0XHQkd3JhcHBlci5maW5kKCcucGwtZm9ybS1ub3RpY2UnKS5yZW1vdmUoKTtcblx0XHRcdGlmICh0eXBlID09PSAnc3VjY2VzcycpIHtcblx0XHRcdFx0JHdyYXBwZXIucHJlcGVuZChodG1sX3N1Y2Nlc3MpO1xuXHRcdFx0fSBlbHNlIGlmICh0eXBlID09PSAnZXJyb3InKSB7XG5cdFx0XHRcdCR3cmFwcGVyLnByZXBlbmQoaHRtbF9mYWxzZSk7XG5cdFx0XHR9XG5cdFx0fSxcblx0fTtcblxuXHRjb25zdCBwbF9sb2dpbiA9IHtcblx0XHRpbml0OiBmdW5jdGlvbiAoKSB7XG5cdFx0XHR0aGlzLmxvZ2luKCk7XG5cdFx0fSxcblxuXHRcdGxvZ2luOiBmdW5jdGlvbiAoKSB7XG5cdFx0XHRsZXQgdGhhdCA9IHRoaXMsXG5cdFx0XHRcdCRmb3JtID0gJCgnI1VIX2xvZ2luX2Zvcm0nKTtcblxuXHRcdFx0aWYgKCRmb3JtLmxlbmd0aCA+IDApIHtcblx0XHRcdFx0dGhpcy5pbml0VmFsaWRhdGlvbigkZm9ybSk7XG5cdFx0XHR9XG5cblx0XHRcdCRmb3JtLmxpdmUoJ3N1Ym1pdCcsIGZ1bmN0aW9uIChlKSB7XG5cdFx0XHRcdGUucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0bGV0ICR0aGlzID0gJChlLmN1cnJlbnRUYXJnZXQpLFxuXHRcdFx0XHRcdGZvcm1EYXRhID0gJHRoaXMuc2VyaWFsaXplT2JqZWN0KCk7XG5cblx0XHRcdFx0aWYgKHR5cGVvZiBwdWJsaWNfYWpheFJlcXVlc3RzLmxvZ2luICE9PSAndW5kZWZpbmVkJykge1xuXHRcdFx0XHRcdHB1YmxpY19hamF4UmVxdWVzdHMubG9naW4uYWJvcnQoKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdHB1YmxpY19hamF4UmVxdWVzdHMubG9naW4gPSAkLmFqYXgoe1xuXHRcdFx0XHRcdHVybDogcGxfZ2xvYmFscy5hamF4VXJsLFxuXHRcdFx0XHRcdHR5cGU6ICdQT1NUJyxcblx0XHRcdFx0XHRkYXRhOiB7XG5cdFx0XHRcdFx0XHRhY3Rpb246ICdVSF9sb2dpbl9hamF4Jyxcblx0XHRcdFx0XHRcdGRhdGE6IGZvcm1EYXRhXG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRiZWZvcmVTZW5kOiBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHQkKCdpbnB1dCcpLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRzdWNjZXNzOiBmdW5jdGlvbiAocmVzKSB7XG5cdFx0XHRcdFx0XHQkKCdpbnB1dCcpLnByb3AoJ2Rpc2FibGVkJywgZmFsc2UpO1xuXHRcdFx0XHRcdFx0aWYgKHJlcy5zdWNjZXNzKSB7XG5cdFx0XHRcdFx0XHRcdHBsX25vdGljZXMuZm9ybXMocmVzLm1zZywgJHRoaXMsICdzdWNjZXNzJyk7XG5cdFx0XHRcdFx0XHRcdHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gcmVzLnJlZGlyZWN0X3VybDtcblx0XHRcdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0XHRcdHBsX25vdGljZXMuZm9ybXMocmVzLm1zZywgJHRoaXMpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0ZXJyb3I6IGZ1bmN0aW9uICh4aHIsIHN0YXR1cywgZXJyb3IpIHtcblx0XHRcdFx0XHRcdHZhciBlcnJvck1lc3NhZ2UgPSB4aHIuc3RhdHVzICsgJzogJyArIHhoci5zdGF0dXNUZXh0O1xuXHRcdFx0XHRcdFx0aWYgKHhoci5zdGF0dXNUZXh0ICE9PSAnYWJvcnQnKSB7XG5cdFx0XHRcdFx0XHRcdGNvbnNvbGUuZXJyb3IoJ0Vycm9yIC0gJyArIGVycm9yTWVzc2FnZSk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9KTtcblx0XHRcdH0pXG5cdFx0fSxcblxuXHRcdGluaXRWYWxpZGF0aW9uOiBmdW5jdGlvbihlbCkge1xuXHRcdFx0ZWwudmFsaWRhdGUoe1xuXHRcdFx0XHRub3JtYWxpemVyOiBmdW5jdGlvbiAodmFsdWUpIHtcblx0XHRcdFx0XHRyZXR1cm4gJC50cmltKHZhbHVlKTtcblx0XHRcdFx0fSxcblx0XHRcdFx0cnVsZXM6IHtcblx0XHRcdFx0XHR1c2VyX2xvZ2luOiBcInJlcXVpcmVkXCIsXG5cdFx0XHRcdFx0dXNlcl9wYXNzd29yZDogXCJyZXF1aXJlZFwiLFxuXHRcdFx0XHR9XG5cdFx0XHR9KTtcblx0XHR9XG5cblx0fTtcblxuXG5cdCQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uIChlKSB7XG5cdFx0cGxfYmFzaWMuaW5pdCgpO1xuXHR9KTtcblxuXHQkKFwiLmN1c3RvbS1maWxlLWlucHV0XCIpLm9uKFwiY2hhbmdlXCIsIGZ1bmN0aW9uKCkge1xuXHRcdHZhciBmaWxlTmFtZSA9ICQodGhpcykudmFsKCkuc3BsaXQoXCJcXFxcXCIpLnBvcCgpO1xuXHRcdCQodGhpcykuc2libGluZ3MoXCIuY3VzdG9tLWZpbGUtbGFiZWxcIikuYWRkQ2xhc3MoXCJzZWxlY3RlZFwiKS5odG1sKGZpbGVOYW1lKTtcblx0fSk7XG5cbn0pKCBqUXVlcnkgKTtcbiJdfQ==
