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