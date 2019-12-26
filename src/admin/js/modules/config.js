// import 3d party modules
import $ from 'jquery';

class Config {

    constructor() {
        this.ajaxRequests = {};
    }

    configAjax(el, option_name, option_value) {
        this.ajaxRequests.switch_settings = $.ajax({
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
}

export default Config;