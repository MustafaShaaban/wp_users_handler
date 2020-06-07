// import plugin 3d party modules
import $ from 'jquery';

// import plugin modules
import FrontUser from "./fronts/front-user";
import FrontProfile from "./fronts/front-profile";

class UHPublic {
    constructor() {
        this.frontUser = new FrontUser();
        this.frontProfile = new FrontProfile();
        this.initialization();
    }

    initialization() {
        const phrases = pl_globals.pl_phrases;

        $.extend($.validator.messages, {
            required: phrases.default,
            email: phrases.email,
            number: phrases.number,
            equalTo: phrases.equalTo,
            maxlength: $.validator.format(phrases.maxLength),
            minlength: $.validator.format(phrases.minLength),
            max: $.validator.format(phrases.max),
            min: $.validator.format(phrases.min)
        });

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

        $.validator.addMethod(
            "email_regex",
            function (value, element) {
                let re = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
                return this.optional(element) || re.test(value);
            },
            phrases.email_regex
        );
        $.validator.addMethod(
            "phone_regex",
            function (value, element) {
                let re = new RegExp("^[0-9]{11,16}$");
                return this.optional(element) || re.test(value);
            },
            phrases.phone_regex
        );
        $.validator.addMethod(
            "password_regex",
            function (value, element) {
                let re = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
                return this.optional(element) || re.test(value);
            },
            phrases.pass_regex
        );
        $.validator.addMethod(
            "regex",
            function (value, element, regex) {
                let re = new RegExp(regex);
                return this.optional(element) || re.test(value);
            },
            phrases.minLength
        );


    }
}

export default UHPublic;