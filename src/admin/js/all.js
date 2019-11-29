/* =============================== Main ===============================  */
(function ($) {
    'use strict';

    const admin_ajaxRequests = {};

    const pl_basic = {
        init: function () {
            pl_settings.init();
        }
    };

    const pl_settings = {
        init: function () {
            this.update_settings();
        },
        update_settings: function () {
            let that = this;
            $('.pl-switch').live('change', function (e) {
                e.preventDefault();
                let $this = $(e.currentTarget),
                    name = $this.attr('name'),
                    option_name = '',
                    option_value = ($this.prop("checked")) ? 'on' : 'off';

                if (typeof admin_ajaxRequests.switch_settings !== 'undefined') {
                    admin_ajaxRequests.switch_settings.abort();
                }

                switch (name) {
                    case 'email_confirmation':
                        option_name = 'email_confirmation';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'limit_active_login':
                        option_name = 'limit_active_login';
                        if (option_value === 'on') {
                            $('.pl-active-login').fadeIn();
                        } else {
                            $('.pl-active-login').fadeOut();
                        }
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'admin_approval':
                        option_name = 'admin_approval';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'check_keyup':
                        option_name = 'check_keyup';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'block_users':
                        option_name = 'block_users';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'login_network':
                        option_name = 'login_network';
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    case 'number_of_active_login':
                        option_name = 'number_of_active_login';
                        option_value = $this.val();
                        that.settings_ajax($this, option_name, option_value);
                        break;
                    default:
                        break;
                }
            });
            $('.pl-setting-input').live('focusout', function (e) {
                e.preventDefault();
                let $this = $(e.currentTarget),
                    name = $this.attr('name'),
                    option_name = '',
                    option_value = $this.val();

                if (typeof admin_ajaxRequests.switch_settings !== 'undefined') {
                    admin_ajaxRequests.switch_settings.abort();
                }

                setTimeout(function () {
                    switch (name) {
                        case 'number_of_active_login':
                            option_name = 'number_of_active_login';
                            that.settings_ajax($this, option_name, option_value);
                            break;
                        default:
                            break;
                    }
                }, 200);
            });
        },

        settings_ajax: function (el, option_name, option_value) {
            admin_ajaxRequests.switch_settings = $.ajax({
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
    };

    $(document).ready(function (e) {
        pl_basic.init();
    });

})(jQuery);
/* =============================== Main ===============================  */
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndwX3VzZXJzX2hhbmRsZXItYWRtaW4uanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJhbGwuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvKiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IE1haW4gPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAgKi9cbihmdW5jdGlvbiAoJCkge1xuICAgICd1c2Ugc3RyaWN0JztcblxuICAgIGNvbnN0IGFkbWluX2FqYXhSZXF1ZXN0cyA9IHt9O1xuXG4gICAgY29uc3QgcGxfYmFzaWMgPSB7XG4gICAgICAgIGluaXQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHBsX3NldHRpbmdzLmluaXQoKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICBjb25zdCBwbF9zZXR0aW5ncyA9IHtcbiAgICAgICAgaW5pdDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy51cGRhdGVfc2V0dGluZ3MoKTtcbiAgICAgICAgfSxcbiAgICAgICAgdXBkYXRlX3NldHRpbmdzOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBsZXQgdGhhdCA9IHRoaXM7XG4gICAgICAgICAgICAkKCcucGwtc3dpdGNoJykubGl2ZSgnY2hhbmdlJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgbGV0ICR0aGlzID0gJChlLmN1cnJlbnRUYXJnZXQpLFxuICAgICAgICAgICAgICAgICAgICBuYW1lID0gJHRoaXMuYXR0cignbmFtZScpLFxuICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICcnLFxuICAgICAgICAgICAgICAgICAgICBvcHRpb25fdmFsdWUgPSAoJHRoaXMucHJvcChcImNoZWNrZWRcIikpID8gJ29uJyA6ICdvZmYnO1xuXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBhZG1pbl9hamF4UmVxdWVzdHMuc3dpdGNoX3NldHRpbmdzICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICBhZG1pbl9hamF4UmVxdWVzdHMuc3dpdGNoX3NldHRpbmdzLmFib3J0KCk7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgc3dpdGNoIChuYW1lKSB7XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ2VtYWlsX2NvbmZpcm1hdGlvbic6XG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICdlbWFpbF9jb25maXJtYXRpb24nO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhhdC5zZXR0aW5nc19hamF4KCR0aGlzLCBvcHRpb25fbmFtZSwgb3B0aW9uX3ZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBjYXNlICdsaW1pdF9hY3RpdmVfbG9naW4nOlxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uX25hbWUgPSAnbGltaXRfYWN0aXZlX2xvZ2luJztcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChvcHRpb25fdmFsdWUgPT09ICdvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkKCcucGwtYWN0aXZlLWxvZ2luJykuZmFkZUluKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJy5wbC1hY3RpdmUtbG9naW4nKS5mYWRlT3V0KCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGF0LnNldHRpbmdzX2FqYXgoJHRoaXMsIG9wdGlvbl9uYW1lLCBvcHRpb25fdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ2FkbWluX2FwcHJvdmFsJzpcbiAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbl9uYW1lID0gJ2FkbWluX2FwcHJvdmFsJztcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoYXQuc2V0dGluZ3NfYWpheCgkdGhpcywgb3B0aW9uX25hbWUsIG9wdGlvbl92YWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnY2hlY2tfa2V5dXAnOlxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uX25hbWUgPSAnY2hlY2tfa2V5dXAnO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhhdC5zZXR0aW5nc19hamF4KCR0aGlzLCBvcHRpb25fbmFtZSwgb3B0aW9uX3ZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBjYXNlICdibG9ja191c2Vycyc6XG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICdibG9ja191c2Vycyc7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGF0LnNldHRpbmdzX2FqYXgoJHRoaXMsIG9wdGlvbl9uYW1lLCBvcHRpb25fdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ2xvZ2luX25ldHdvcmsnOlxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uX25hbWUgPSAnbG9naW5fbmV0d29yayc7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGF0LnNldHRpbmdzX2FqYXgoJHRoaXMsIG9wdGlvbl9uYW1lLCBvcHRpb25fdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ251bWJlcl9vZl9hY3RpdmVfbG9naW4nOlxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uX25hbWUgPSAnbnVtYmVyX29mX2FjdGl2ZV9sb2dpbic7XG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fdmFsdWUgPSAkdGhpcy52YWwoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoYXQuc2V0dGluZ3NfYWpheCgkdGhpcywgb3B0aW9uX25hbWUsIG9wdGlvbl92YWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgZGVmYXVsdDpcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgJCgnLnBsLXNldHRpbmctaW5wdXQnKS5saXZlKCdmb2N1c291dCcsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgIGxldCAkdGhpcyA9ICQoZS5jdXJyZW50VGFyZ2V0KSxcbiAgICAgICAgICAgICAgICAgICAgbmFtZSA9ICR0aGlzLmF0dHIoJ25hbWUnKSxcbiAgICAgICAgICAgICAgICAgICAgb3B0aW9uX25hbWUgPSAnJyxcbiAgICAgICAgICAgICAgICAgICAgb3B0aW9uX3ZhbHVlID0gJHRoaXMudmFsKCk7XG5cbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGFkbWluX2FqYXhSZXF1ZXN0cy5zd2l0Y2hfc2V0dGluZ3MgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgIGFkbWluX2FqYXhSZXF1ZXN0cy5zd2l0Y2hfc2V0dGluZ3MuYWJvcnQoKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgc3dpdGNoIChuYW1lKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBjYXNlICdudW1iZXJfb2ZfYWN0aXZlX2xvZ2luJzpcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICdudW1iZXJfb2ZfYWN0aXZlX2xvZ2luJztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGF0LnNldHRpbmdzX2FqYXgoJHRoaXMsIG9wdGlvbl9uYW1lLCBvcHRpb25fdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICAgICAgZGVmYXVsdDpcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sIDIwMCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSxcblxuICAgICAgICBzZXR0aW5nc19hamF4OiBmdW5jdGlvbiAoZWwsIG9wdGlvbl9uYW1lLCBvcHRpb25fdmFsdWUpIHtcbiAgICAgICAgICAgIGFkbWluX2FqYXhSZXF1ZXN0cy5zd2l0Y2hfc2V0dGluZ3MgPSAkLmFqYXgoe1xuICAgICAgICAgICAgICAgIHVybDogcGxfZ2xvYmFscy5hamF4VXJsLFxuICAgICAgICAgICAgICAgIHR5cGU6ICdQT1NUJyxcbiAgICAgICAgICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogcGxfZ2xvYmFscy5wbHVnaW5fa2V5ICsgJ19zd2l0Y2hfc2V0dGluZ3MnLFxuICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZTogb3B0aW9uX25hbWUsXG4gICAgICAgICAgICAgICAgICAgIG9wdGlvbl92YWx1ZTogb3B0aW9uX3ZhbHVlLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgYmVmb3JlU2VuZDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBlbC5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHJlcykge1xuICAgICAgICAgICAgICAgICAgICBpZiAocmVzLnN1Y2Nlc3MpIHtcblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBjb21wbGV0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBlbC5wcm9wKCdkaXNhYmxlZCcsIGZhbHNlKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoZSkge1xuICAgICAgICBwbF9iYXNpYy5pbml0KCk7XG4gICAgfSk7XG5cbn0pKGpRdWVyeSk7XG4vKiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IE1haW4gPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAgKi8iXX0=
