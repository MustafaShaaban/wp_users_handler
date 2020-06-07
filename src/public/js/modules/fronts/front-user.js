// import plugin 3d party modules
import $ from 'jquery';
import _ from 'lodash';

// import plugin modules
import UICtr from '../uiCtrl';
import User from '../core/user';

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

            if ($this.valid()) {
                that.Users.loginUser(formData, $this);
            }


        });
    }

    register() {
        let that = this,
            $register = this.UI.usersUI.reg_form,
            ajaxRequests = this.Users.ajaxRequests;
        this.initValidation('reg');

        $register.form.on('submit', $register.parent, function (e) {
            e.preventDefault();
            let $this = $(e.currentTarget),
                formData = $this.serializeObject();

            if (typeof ajaxRequests.register !== 'undefined') {
                ajaxRequests.register.abort();
            }

            if ($this.valid()) {
                that.Users.registerUser(formData, $this);
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
                            user_login: {
                                required: true,
                                minlength: 5
                            },
                            user_password: "required",
                        }
                    });
                }
            },
            reg: function () {
                if (that.UI.usersUI.reg_form.form.length > 0) {
                    that.UI.usersUI.reg_form.form.validate({
                        normalizer: function (value) {
                            return $.trim(value);
                        },
                        rules: {
                            first_name: {
                                required: true,
                                minlength: 5,
                                maxlength: 50,
                            },
                            last_name: {
                                required: true,
                                minlength: 5,
                                maxlength: 50,
                            },
                            user_login: {
                                required: true,
                                minlength: 5,
                                maxlength: 50,
                                remote: {
                                    url: pl_globals.ajaxUrl,
                                    type: "POST",
                                    data: {
                                        action: `${KEY}_check_username_ajax`,
                                        username: function () {
                                            return $(`#${KEY}_user_login`);
                                        }
                                    },
                                    error: function (xhr) {
                                        let errorMessage = `${xhr.status}: ${xhr.statusText}`;
                                        if (xhr.statusText !== 'abort') {
                                            console.error(errorMessage);
                                        }
                                    }
                                }
                            },
                            user_password: {
                                required: true,
                                password_regex: true
                            },
                            user_password_confirm: {
                                required: true,
                                equalTo: `#${KEY}_user_password`,
                                password_regex: true
                            },
                            user_email: {
                                required: true,
                                email: true,
                                email_regex: true,
                                remote: {
                                    url: pl_globals.ajaxUrl,
                                    type: "POST",
                                    data: {
                                        action: `${KEY}_check_email_ajax`,
                                        email: function () {
                                            return $(`#${KEY}_user_email`);
                                        }
                                    },
                                    error: function (xhr) {
                                        let errorMessage = `${xhr.status}: ${xhr.statusText}`;
                                        if (xhr.statusText !== 'abort') {
                                            console.error(errorMessage);
                                        }
                                    }
                                }
                            },
                            user_email_confirm: {
                                required: true,
                                email: true,
                                equalTo: `#${KEY}_user_email`,
                                email_regex: true
                            },
                        },
                        messages: {
                            user_password_confirm: {
                                equalTo: pl_globals.pl_phrases.confirm_password
                            },
                            user_email_confirm: {
                                equalTo: pl_globals.pl_phrases.confirm_email
                            }
                        }
                    });
                }
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