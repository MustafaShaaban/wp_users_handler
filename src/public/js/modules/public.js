// import plugin 3d party modules
import $ from 'jquery';

// import plugin modules
import FrontUser from "./front/front-user";
import FrontProfile from "./front/front-profile";

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
            maxlength: $.validator.format(phrases.maxlength),
            minlength: $.validator.format(phrases.minlength),
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
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            phrases.email_regex
        );
        $.validator.addMethod(
            "phone_regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            phrases.phone_regex
        );
        $.validator.addMethod(
            "password_regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            phrases.pass_regex
        );


    }
}

export default UHPublic;