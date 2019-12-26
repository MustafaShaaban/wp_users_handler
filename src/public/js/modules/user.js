// import plugin 3d party modules
import $ from 'jquery';

// import plugin modules
import UICtr from './uiCtrl';

class User {
    constructor() {
        this.UICtr = new UICtr();
        this.ajaxRequests = {};
    }

    loginUser(formData, el) {
        let that = this;
        this.ajaxRequests.login = $.ajax({
            url: pl_globals.ajaxUrl,
            type: 'POST',
            data: {
                action: `${KEY}_login_ajax`,
                data: formData
            },
            beforeSend: function () {
                el.find('input, textarea, button').prop('disabled', true);
                that.UICtr.beforeSendPrepare(el);
            },
            success: function (res) {
                $('input').prop('disabled', false);
                if (res.success) {
                    UICtr.notices(el, res.msg,'success');
                    window.location.href = res.redirect_url;
                } else {
                    UICtr.notices(el, res.msg);
                }
                el.find('input, textarea, button').prop('disabled', false);
                that.UICtr.blockUI(el, false);
            },
            error: function (xhr) {
                let errorMessage = `${xhr.status}: ${xhr.statusText}`;
                if (xhr.statusText !== 'abort') {
                    console.error(errorMessage);
                }
            }
        });
    }

    emptyForm(form) {
        form.find(':input:not([type=hidden], [type=checkbox], [type=radio])').val('');
        form.find('textarea').val('');
    };
}
export default User;