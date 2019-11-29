/* =============================== Main ===============================  */
(function ($) {
    'use strict';

    const admin_ajaxRequests = {};

    const pl_basic = {
        init: function () {
            pl_settings.init();
        }
    };

    const pl_settings = {
        init: function () {
            this.update_settings();
        },
        update_settings: function () {
            let that = this;
            $('.pl-switch').live('change', function (e) {
                e.preventDefault();
                let $this = $(e.currentTarget),
                    name = $this.attr('name'),
                    option_name = '',
                    option_value = ($this.prop("checked")) ? 'on' : 'off';

                if (typeof admin_ajaxRequests.switch_settings !== 'undefined') {
                    admin_ajaxRequests.switch_settings.abort();
                }

                switch (name) {
                    case 'email_confirmation':
                        option_name = 'email_confirmation';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'limit_active_login':
                        option_name = 'limit_active_login';
                        if (option_value === 'on') {
                            $('.pl-active-login').fadeIn();
                        } else {
                            $('.pl-active-login').fadeOut();
                        }
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'admin_approval':
                        option_name = 'admin_approval';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'check_keyup':
                        option_name = 'check_keyup';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'block_users':
                        option_name = 'block_users';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'login_network':
                        option_name = 'login_network';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'number_of_active_login':
                        option_name = 'number_of_active_login';
                        option_value = $this.val();
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    default:
                        break;
                }
            });
            $('.pl-setting-input').live('focusout', function (e) {
                e.preventDefault();
                let $this = $(e.currentTarget),
                    name = $this.attr('name'),
                    option_name = '',
                    option_value = $this.val();

                if (typeof admin_ajaxRequests.switch_settings !== 'undefined') {
                    admin_ajaxRequests.switch_settings.abort();
                }

                setTimeout(function () {
                    switch (name) {
                        case 'number_of_active_login':
                            option_name = 'number_of_active_login';
                            that.settings_ajax($this, option_name, option_value);
                            break;
                        default:
                            break;
                    }
                }, 200);
            });
        },

        settings_ajax: function (el, option_name, option_value) {
            admin_ajaxRequests.switch_settings = $.ajax({
                url: pl_globals.ajaxUrl,
                type: 'POST',
                data: {
                    action: pl_globals.plugin_key + '_switch_settings',
                    option_name: option_name,
                    option_value: option_value,
                },
                beforeSend: function () {
                    el.prop('disabled', true);
                },
                success: function (res) {
                    if (res.success) {

                    }
                },
                complete: function () {
                    el.prop('disabled', false);
                }
            });
        }
    };

    $(document).ready(function (e) {
        pl_basic.init();
    });

})(jQuery);
/* =============================== Main ===============================  */