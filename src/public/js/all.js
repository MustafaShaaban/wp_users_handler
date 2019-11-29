/* =============================== Functions ===============================  */
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
/* =============================== Functions ===============================  */
/* =============================== Main ===============================  */
(function ($) {
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
                function (value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                pl_phrases.email_regex
            );
            $.validator.addMethod(
                "phone_regex",
                function (value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                pl_phrases.phone_regex
            );
            $.validator.addMethod(
                "password_regex",
                function (value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                pl_phrases.pass_regex
            );
            $.validator.setDefaults({
                errorPlacement: function (label, element) {
                    label.addClass('pl-error');
                    label.insertAfter(element);
                },
                highlight: function (element) {
                    $(element).addClass('pl-error-input');
                },
                unhighlight: function (element) {
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

        initValidation: function (el) {
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

    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

})(jQuery);
/* =============================== Main ===============================  */
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImZ1bmN0aW9ucy5qcyIsIndwX3VzZXJzX2hhbmRsZXItcHVibGljLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUNqREE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiYWxsLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSBGdW5jdGlvbnMgPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAgKi9cclxuKGZ1bmN0aW9uICgkKSB7XHJcbiAgICAkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICQuZm4uc2VyaWFsaXplT2JqZWN0ID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBcInVzZSBzdHJpY3RcIjtcclxuICAgICAgICAgICAgdmFyIGEgPSB7fSwgYiA9IGZ1bmN0aW9uIChiLCBjKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgZCA9IGFbYy5uYW1lXTtcclxuICAgICAgICAgICAgICAgIFwidW5kZWZpbmVkXCIgIT0gdHlwZW9mIGQgJiYgZCAhPT0gbnVsbCA/ICQuaXNBcnJheShkKSA/IGQucHVzaChjLnZhbHVlKSA6IGFbYy5uYW1lXSA9IFtkLCBjLnZhbHVlXSA6IGFbYy5uYW1lXSA9IGMudmFsdWVcclxuICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgcmV0dXJuICQuZWFjaCh0aGlzLnNlcmlhbGl6ZUFycmF5KCksIGIpLCBhXHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgd2luZG93LnBsX2NyZWF0ZUxvYWRlciA9IGZ1bmN0aW9uICh3cmFwcGVyLCB0eXBlID0gdHJ1ZSkge1xyXG4gICAgICAgICAgICBpZiAodHlwZSA9PT0gdHJ1ZSkge1xyXG4gICAgICAgICAgICAgICAgJCgnLmxnLWxvYWRlcicpLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgd3JhcHBlci5hcHBlbmQoJzxkaXYgY2xhc3M9XCJjc3Nsb2FkLWNvbnRhaW5lciBsZy1sb2FkZXJcIj48ZGl2IGNsYXNzPVwiY3NzbG9hZC1sb2FkaW5nXCI+PGk+PC9pPjxpPjwvaT48aT48L2k+PGk+PC9pPjwvZGl2PjwvZGl2PicpO1xyXG4gICAgICAgICAgICAgICAgJChcIi5sZy1sb2FkZXJcIikuYW5pbWF0ZSh7XHJcbiAgICAgICAgICAgICAgICAgICAgb3BhY2l0eTogMVxyXG4gICAgICAgICAgICAgICAgfSwgMjUwKTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICQoJy5sZy1sb2FkZXInKS5mYWRlT3V0KDI1MCwgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICQoJy5sZy1sb2FkZXInKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgd2luZG93LnBsX2NyZWF0ZU5vdGljZSA9IGZ1bmN0aW9uICh3cmFwcGVyLCBtc2csIGZsYWcgPSAnc3VjY2VzcycsIHR5cGUgPSB0cnVlKSB7XHJcblxyXG4gICAgICAgICAgICBpZiAodHlwZSA9PT0gdHJ1ZSkge1xyXG4gICAgICAgICAgICAgICAgJCgnLm1lc3NhZ2UnKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgIHdyYXBwZXIuYXBwZW5kKCc8cCBjbGFzcz1cIm1lc3NhZ2UgbGctbWVzc2FnZXMgbWVzc2FnZS0nICsgZmxhZyArICdcIj4nICsgbXNnICsgJzwvcD4nKTtcclxuICAgICAgICAgICAgICAgICQoXCIubWVzc2FnZVwiKS5hbmltYXRlKHtcclxuICAgICAgICAgICAgICAgICAgICBvcGFjaXR5OiAxXHJcbiAgICAgICAgICAgICAgICB9LCAyMDApO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgJCgnLm1lc3NhZ2UnKS5mYWRlT3V0KDIwMCwgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICQoJy5tZXNzYWdlJykucmVtb3ZlKCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIHdpbmRvdy5wbF9lbXB0eUZvcm0gPSBmdW5jdGlvbiAoZm9ybSkge1xyXG4gICAgICAgICAgICBmb3JtLmZpbmQoJzppbnB1dDpub3QoW3R5cGU9aGlkZGVuXSwgW3R5cGU9Y2hlY2tib3hdKScpLnZhbCgnJyk7XHJcbiAgICAgICAgICAgIGZvcm0uZmluZCgndGV4dGFyZWEnKS52YWwoJycpO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgfSk7XHJcbn0pKGpRdWVyeSk7XHJcbi8qID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gRnVuY3Rpb25zID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gICovIiwiLyogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSBNYWluID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gICovXG4oZnVuY3Rpb24gKCQpIHtcbiAgICAndXNlIHN0cmljdCc7XG5cbiAgICBjb25zdCBwdWJsaWNfYWpheFJlcXVlc3RzID0ge307XG5cbiAgICBjb25zdCBwbF9iYXNpYyA9IHtcbiAgICAgICAgaW5pdDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5wcmVwYXJlKCk7XG4gICAgICAgICAgICBwbF9sb2dpbi5pbml0KCk7XG4gICAgICAgIH0sXG4gICAgICAgIHByZXBhcmU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQudmFsaWRhdG9yLmFkZE1ldGhvZChcbiAgICAgICAgICAgICAgICBcImVtYWlsX3JlZ2V4XCIsXG4gICAgICAgICAgICAgICAgZnVuY3Rpb24gKHZhbHVlLCBlbGVtZW50LCByZWdleHApIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHJlID0gbmV3IFJlZ0V4cChyZWdleHApO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5vcHRpb25hbChlbGVtZW50KSB8fCByZS50ZXN0KHZhbHVlKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHBsX3BocmFzZXMuZW1haWxfcmVnZXhcbiAgICAgICAgICAgICk7XG4gICAgICAgICAgICAkLnZhbGlkYXRvci5hZGRNZXRob2QoXG4gICAgICAgICAgICAgICAgXCJwaG9uZV9yZWdleFwiLFxuICAgICAgICAgICAgICAgIGZ1bmN0aW9uICh2YWx1ZSwgZWxlbWVudCwgcmVnZXhwKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciByZSA9IG5ldyBSZWdFeHAocmVnZXhwKTtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMub3B0aW9uYWwoZWxlbWVudCkgfHwgcmUudGVzdCh2YWx1ZSk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBwbF9waHJhc2VzLnBob25lX3JlZ2V4XG4gICAgICAgICAgICApO1xuICAgICAgICAgICAgJC52YWxpZGF0b3IuYWRkTWV0aG9kKFxuICAgICAgICAgICAgICAgIFwicGFzc3dvcmRfcmVnZXhcIixcbiAgICAgICAgICAgICAgICBmdW5jdGlvbiAodmFsdWUsIGVsZW1lbnQsIHJlZ2V4cCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgcmUgPSBuZXcgUmVnRXhwKHJlZ2V4cCk7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLm9wdGlvbmFsKGVsZW1lbnQpIHx8IHJlLnRlc3QodmFsdWUpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcGxfcGhyYXNlcy5wYXNzX3JlZ2V4XG4gICAgICAgICAgICApO1xuICAgICAgICAgICAgJC52YWxpZGF0b3Iuc2V0RGVmYXVsdHMoe1xuICAgICAgICAgICAgICAgIGVycm9yUGxhY2VtZW50OiBmdW5jdGlvbiAobGFiZWwsIGVsZW1lbnQpIHtcbiAgICAgICAgICAgICAgICAgICAgbGFiZWwuYWRkQ2xhc3MoJ3BsLWVycm9yJyk7XG4gICAgICAgICAgICAgICAgICAgIGxhYmVsLmluc2VydEFmdGVyKGVsZW1lbnQpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgaGlnaGxpZ2h0OiBmdW5jdGlvbiAoZWxlbWVudCkge1xuICAgICAgICAgICAgICAgICAgICAkKGVsZW1lbnQpLmFkZENsYXNzKCdwbC1lcnJvci1pbnB1dCcpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgdW5oaWdobGlnaHQ6IGZ1bmN0aW9uIChlbGVtZW50KSB7XG4gICAgICAgICAgICAgICAgICAgICQoZWxlbWVudCkucmVtb3ZlQ2xhc3MoJ3BsLWVycm9yLWlucHV0Jyk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAkLmV4dGVuZCgkLnZhbGlkYXRvci5tZXNzYWdlcywge1xuICAgICAgICAgICAgICAgIHJlcXVpcmVkOiBwbF9waHJhc2VzLmRlZmF1bHQsXG4gICAgICAgICAgICAgICAgZW1haWw6IHBsX3BocmFzZXMuZW1haWwsXG4gICAgICAgICAgICAgICAgbnVtYmVyOiBwbF9waHJhc2VzLm51bWJlcixcbiAgICAgICAgICAgICAgICBlcXVhbFRvOiBwbF9waHJhc2VzLmVxdWFsVG8sXG4gICAgICAgICAgICAgICAgbWF4bGVuZ3RoOiAkLnZhbGlkYXRvci5mb3JtYXQocGxfcGhyYXNlcy5tYXhsZW5ndGgpLFxuICAgICAgICAgICAgICAgIG1pbmxlbmd0aDogJC52YWxpZGF0b3IuZm9ybWF0KHBsX3BocmFzZXMubWlubGVuZ3RoKSxcbiAgICAgICAgICAgICAgICBtYXg6ICQudmFsaWRhdG9yLmZvcm1hdChwbF9waHJhc2VzLm1heCksXG4gICAgICAgICAgICAgICAgbWluOiAkLnZhbGlkYXRvci5mb3JtYXQocGxfcGhyYXNlcy5taW4pXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBjb25zdCBwbF9ub3RpY2VzID0ge1xuICAgICAgICBpbml0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmZvcm1zKCk7XG4gICAgICAgIH0sXG4gICAgICAgIGZvcm1zOiBmdW5jdGlvbiAobXNnLCAkd3JhcHBlciwgdHlwZSA9ICdlcnJvcicpIHtcbiAgICAgICAgICAgIGxldCBodG1sX3N1Y2Nlc3MgPSAnPGRpdiBjbGFzcz1cInBsLWZvcm0tbm90aWNlXCI+PGRpdiBjbGFzcz1cInN1Y2Nlc3NcIj48cD4nICsgbXNnICsgJzwvcD48L2Rpdj48L2Rpdj4nLFxuICAgICAgICAgICAgICAgIGh0bWxfZmFsc2UgPSAnPGRpdiBjbGFzcz1cInBsLWZvcm0tbm90aWNlXCI+PGRpdiBjbGFzcz1cImVycm9yXCI+PHA+JyArIG1zZyArICc8L3A+PC9kaXY+PC9kaXY+JztcbiAgICAgICAgICAgICR3cmFwcGVyLmZpbmQoJy5wbC1mb3JtLW5vdGljZScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgaWYgKHR5cGUgPT09ICdzdWNjZXNzJykge1xuICAgICAgICAgICAgICAgICR3cmFwcGVyLnByZXBlbmQoaHRtbF9zdWNjZXNzKTtcbiAgICAgICAgICAgIH0gZWxzZSBpZiAodHlwZSA9PT0gJ2Vycm9yJykge1xuICAgICAgICAgICAgICAgICR3cmFwcGVyLnByZXBlbmQoaHRtbF9mYWxzZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG4gICAgfTtcblxuICAgIGNvbnN0IHBsX2xvZ2luID0ge1xuICAgICAgICBpbml0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmxvZ2luKCk7XG4gICAgICAgIH0sXG5cbiAgICAgICAgbG9naW46IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGxldCB0aGF0ID0gdGhpcyxcbiAgICAgICAgICAgICAgICAkZm9ybSA9ICQoJyNVSF9sb2dpbl9mb3JtJyk7XG5cbiAgICAgICAgICAgIGlmICgkZm9ybS5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5pbml0VmFsaWRhdGlvbigkZm9ybSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICRmb3JtLmxpdmUoJ3N1Ym1pdCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgIGxldCAkdGhpcyA9ICQoZS5jdXJyZW50VGFyZ2V0KSxcbiAgICAgICAgICAgICAgICAgICAgZm9ybURhdGEgPSAkdGhpcy5zZXJpYWxpemVPYmplY3QoKTtcblxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgcHVibGljX2FqYXhSZXF1ZXN0cy5sb2dpbiAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICAgICAgcHVibGljX2FqYXhSZXF1ZXN0cy5sb2dpbi5hYm9ydCgpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHB1YmxpY19hamF4UmVxdWVzdHMubG9naW4gPSAkLmFqYXgoe1xuICAgICAgICAgICAgICAgICAgICB1cmw6IHBsX2dsb2JhbHMuYWpheFVybCxcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ1BPU1QnLFxuICAgICAgICAgICAgICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBhY3Rpb246ICdVSF9sb2dpbl9hamF4JyxcbiAgICAgICAgICAgICAgICAgICAgICAgIGRhdGE6IGZvcm1EYXRhXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIGJlZm9yZVNlbmQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJ2lucHV0JykucHJvcCgnZGlzYWJsZWQnLCB0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHJlcykge1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnaW5wdXQnKS5wcm9wKCdkaXNhYmxlZCcsIGZhbHNlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChyZXMuc3VjY2Vzcykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBsX25vdGljZXMuZm9ybXMocmVzLm1zZywgJHRoaXMsICdzdWNjZXNzJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSByZXMucmVkaXJlY3RfdXJsO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwbF9ub3RpY2VzLmZvcm1zKHJlcy5tc2csICR0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZXJyb3I6IGZ1bmN0aW9uICh4aHIsIHN0YXR1cywgZXJyb3IpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBlcnJvck1lc3NhZ2UgPSB4aHIuc3RhdHVzICsgJzogJyArIHhoci5zdGF0dXNUZXh0O1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXNUZXh0ICE9PSAnYWJvcnQnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5lcnJvcignRXJyb3IgLSAnICsgZXJyb3JNZXNzYWdlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSlcbiAgICAgICAgfSxcblxuICAgICAgICBpbml0VmFsaWRhdGlvbjogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgICAgICBlbC52YWxpZGF0ZSh7XG4gICAgICAgICAgICAgICAgbm9ybWFsaXplcjogZnVuY3Rpb24gKHZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAkLnRyaW0odmFsdWUpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgcnVsZXM6IHtcbiAgICAgICAgICAgICAgICAgICAgdXNlcl9sb2dpbjogXCJyZXF1aXJlZFwiLFxuICAgICAgICAgICAgICAgICAgICB1c2VyX3Bhc3N3b3JkOiBcInJlcXVpcmVkXCIsXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgIH07XG5cbiAgICAkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoZSkge1xuICAgICAgICBwbF9iYXNpYy5pbml0KCk7XG4gICAgfSk7XG5cbiAgICAkKFwiLmN1c3RvbS1maWxlLWlucHV0XCIpLm9uKFwiY2hhbmdlXCIsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGZpbGVOYW1lID0gJCh0aGlzKS52YWwoKS5zcGxpdChcIlxcXFxcIikucG9wKCk7XG4gICAgICAgICQodGhpcykuc2libGluZ3MoXCIuY3VzdG9tLWZpbGUtbGFiZWxcIikuYWRkQ2xhc3MoXCJzZWxlY3RlZFwiKS5odG1sKGZpbGVOYW1lKTtcbiAgICB9KTtcblxufSkoalF1ZXJ5KTtcbi8qID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gTWFpbiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09ICAqLyJdfQ==
