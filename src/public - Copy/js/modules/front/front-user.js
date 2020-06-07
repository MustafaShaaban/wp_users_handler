// import plugin 3d party modules
import $ from 'jquery';
import _ from 'lodash';

// import plugin modules
import UICtr from '../uiCtrl';
import User from '../user';

class FrontUser {
    constructor() {
        this.UI = new UICtr();
        this.Users = new User();
        this.initialization();
    }

    initialization() {
        this.login();
        this.register();
        this.rp_sendEmail();
        this.rp_reset();
        this.activateAccount();
        this.resendActivation();
        this.deleteAccount();
        this.editAccount();
    }

    login() {
        let that = this,
            $login = this.UI.usersUI.login,
            ajaxRequests = this.Users.ajaxRequests;
        this.initValidation('login');

        $login.form.on('submit', $login.parent, function (e) {
            e.preventDefault();
            let $this = $(e.currentTarget),
                formData = $this.serializeObject();

            if (typeof ajaxRequests.login !== 'undefined') {
                ajaxRequests.login.abort();
            }

            if($this.valid()) {
                that.Users.loginUser(formData, $this);
            }


        });
    }

    register() {
        let that = this,
            $login = this.UI.usersUI.reg_form,
            ajaxRequests = this.Users.ajaxRequests;
        this.initValidation('login');

        $login.form.on('submit', $login.parent, function (e) {
            e.preventDefault();
            let $this = $(e.currentTarget),
                formData = $this.serializeObject();

            if (typeof ajaxRequests.login !== 'undefined') {
                ajaxRequests.login.abort();
            }

            if($this.valid()) {
                that.Users.loginUser(formData, $this);
            }


        });
    }

    rp_sendEmail() {
    }

    rp_reset() {
    }

    activateAccount() {
    }

    resendActivation() {
    }

    deleteAccount() {
    }

    editAccount() {
    }

    initValidation(type) {

        let that = this;

        const forms = {
            login: function () {
                if (that.UI.usersUI.login.form.length > 0) {
                    that.UI.usersUI.login.form.validate({
                        normalizer: function (value) {
                            return $.trim(value);
                        },
                        rules: {
                            user_login: "required",
                            user_password: "required",
                        }
                    });

                    // return that.UI.usersUI.login.form.valid();
                }
            },
            reg() {
            },
            rp_endEmail() {
            },
            rp() {
            },
            activateAccount() {
            },
            editAccount() {
            }
        };

        if (_.has(forms, type)) {
            _.invoke(forms, type);
        }
    }


}

export default FrontUser;