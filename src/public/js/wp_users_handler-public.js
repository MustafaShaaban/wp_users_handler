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