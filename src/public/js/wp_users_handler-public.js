// import plugin 3d party modules
import $ from 'jquery';
import B from 'bootstrap/dist/js/bootstrap.bundle';
import validate from 'jquery-validation';

// import plugin modules
import UHPublic from "./modules/public";

$(document).ready(function (e) {
    window.KEY = pl_globals.pl_key;

    $.fn.serializeObject = function () {
        "use strict";
        var a = {}, b = function (b, c) {
            var d = a[c.name];
            "undefined" != typeof d && d !== null ? $.isArray(d) ? d.push(c.value) : a[c.name] = [d, c.value] : a[c.name] = c.value
        };
        return $.each(this.serializeArray(), b), a
    };

    const UH = new UHPublic();
});