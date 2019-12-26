// import plugin 3d party modules
import $ from 'jquery'

// import plugin modules

class UICtr {
    constructor() {
        this.usersUI = {
            login: {
                form: $(`#${KEY}_login_form`),
                parent: $(`#${KEY}_login_form`).parent(),
            },
            reg_form: '',
            rp_endEmail_Form: '',
            rp_Form: '',
            activateAccount_form: '',
            editAccount_form: '',
        };

        $(".custom-file-input").on("change", function () {
            let fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    }

    static notices(el, msg, type) {

        if (typeof el === 'undefined' || typeof msg === 'undefined' || el === '' || msg === '') return;

        const options = {
            msg: msg,
            el: el,
            type: (type === undefined) ? 'error' : 'success'
        };

        const $notice = `
                <div class="${KEY}-form-notice">
                    <div class="${options.type}">
                        <p>${options.msg}</p>
                    </div>
                </div>`;

        $(`.${KEY}-form-notice`).remove();

        return options.el.prepend($notice);
    }

    beforeSendPrepare(el) {
        $(`.${KEY}-form-notice`).remove();
        this.blockUI(el);
    }

    blockUI(el, type = true) {

        const loader = ` <div class="${KEY}-loader"> <div class="loader loader--style1" title="0"> <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve"> <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946 s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634 c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/> <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0 C22.32,8.481,24.301,9.057,26.013,10.047z"> <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatCount="indefinite"/> </path> </svg> </div> </div>`;

        if (type === true) {
            $(`.${KEY}-loader`).remove();
            el.css({position: 'relative'});
            el.append(loader);
            $(`.${KEY}-loader`).animate({
                opacity: 1
            }, 250);
        } else {
            el.css({position: 'auto'});
            $(`.${KEY}-loader`).fadeOut(100, function () {
                $(`.${KEY}-loader`).remove();
            });
        }

    }

}

export default UICtr;