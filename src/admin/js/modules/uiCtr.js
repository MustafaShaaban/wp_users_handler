// import 3d party modules
import $ from 'jquery';

// import plugin modules
import Config from './config'; 

class UICtr {

    constructor() {
        let that = this;
        $(document).ready(function(e) {
            that.config = new Config();
            that.uiInit();
            that.updateConfig();
        });
    }

    uiInit() {
        (function($){
            $('.tabs').tabs();
        })(jQuery)
    }

    updateConfig() {
        let that = this;
        $('#pl_settings').on('change','.pl-switch', function (e) {
            e.preventDefault();
            let $this = $(e.currentTarget),
                name = $this.attr('name'),
                option_name = '',
                option_value = ($this.prop("checked")) ? 'on' : 'off';

            if (typeof that.config.ajaxRequests.switch_settings !== 'undefined') {
                that.config.ajaxRequests.switch_settings.abort();
            }

            switch (name) {
                case 'email_confirmation':
                    option_name = 'email_confirmation';
                    that.config.configAjax($this, option_name, option_value);
                    break;
                case 'limit_active_login':
                    option_name = 'limit_active_login';
                    if (option_value === 'on') {
                        $('.pl-active-login').fadeIn();
                    } else {
                        $('.pl-active-login').fadeOut();
                    }
                    that.config.configAjax($this, option_name, option_value);
                    break;
                case 'admin_approval':
                    option_name = 'admin_approval';
                    that.config.configAjax($this, option_name, option_value);
                    break;
                case 'check_keyup':
                    option_name = 'check_keyup';
                    that.config.configAjax($this, option_name, option_value);
                    break;
                case 'block_users':
                    option_name = 'block_users';
                    that.config.configAjax($this, option_name, option_value);
                    break;
                case 'login_network':
                    option_name = 'login_network';
                    that.config.configAjax($this, option_name, option_value);
                    break;
                case 'number_of_active_login':
                    option_name = 'number_of_active_login';
                    option_value = $this.val();
                    that.config.configAjax($this, option_name, option_value);
                    break;
                default:
                    break;
            }
        });

        // $('#pl-switch').on('focusout', '.pl-setting-input',function (e) {
        //     e.preventDefault();
        //     let $this = $(e.currentTarget),
        //         name = $this.attr('name'),
        //         option_name = '',
        //         option_value = $this.val();

        //     if (typeof ajaxRequests.switch_settings !== 'undefined') {
        //         ajaxRequests.switch_settings.abort();
        //     }

        //     setTimeout(function () {
        //         switch (name) {
        //             case 'number_of_active_login':
        //                 option_name = 'number_of_active_login';
        //                 that.settings_ajax($this, option_name, option_value);
        //                 break;
        //             default:
        //                 break;
        //         }
        //     }, 200);
        // });
    }
}

export default UICtr;