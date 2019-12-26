// import 3d party modules
import $ from 'jquery';

// import plugin modules
import UICtr from './uiCtr';
import Config from './config';

class Front {
    constructor() {
        this.config = new Config();
        this.uiCtr = new UICtr();
        this.updateConfig();
    }

    updateConfig() {
        let that = this;

        $('#pl_settings').on('change', '.pl-switch', function (e) {
            e.preventDefault();
            let $this = $(e.currentTarget),
                name = $this.attr('name'),
                option_name = '',
                option_value = $this.prop("checked") ? 'on' : 'off';

            if (typeof that.config.ajaxRequests['switch_settings'] !== 'undefined') {
                that.config.ajaxRequests['switch_settings'].abort();
            }

            switch (name) {
                case 'email_confirmation':
                    option_name = 'email_confirmation';
                    break;
                case 'admin_approval':
                    option_name = 'admin_approval';
                    break;
                case 'check_keyup':
                    option_name = 'check_keyup';
                    break;
                case 'block_users':
                    option_name = 'block_users';
                    break;
                case 'login_network':
                    option_name = 'login_network';
                    break;
                case 'number_of_active_login':
                    option_name = 'number_of_active_login';
                    option_value = $this.val();
                    break;
                case 'limit_active_login':
                    option_name = 'limit_active_login';
                    if (option_value === 'on') {
                        $('.pl-active-login').fadeIn();
                    } else {
                        $('.pl-active-login').fadeOut();
                    }
                    break;
                default:
                    break;
            }

            that.config.configAjax($this, option_name, option_value);
        });

        $('#pl-switch').on('focusout', '.pl-setting-input',function (e) {
            e.preventDefault();
            let $this = $(e.currentTarget),
                option_value = $this.val();

            if (typeof that.config.ajaxRequests['switch_settings'] !== 'undefined') {
                that.config.ajaxRequests['switch_settings'].abort();
            }

            setTimeout(function () {
                let option_name = 'number_of_active_login';
                that.config.configAjax($this, option_name, option_value);
            }, 200);

        });
    }

}

export default Front;