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

//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndwX3VzZXJzX2hhbmRsZXItYWRtaW4uanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiYWxsLmpzIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uICgkKSB7XG4gICAgJ3VzZSBzdHJpY3QnO1xuXG4gICAgY29uc3QgYWRtaW5fYWpheFJlcXVlc3RzID0ge307XG5cbiAgICBjb25zdCBwbF9iYXNpYyA9IHtcbiAgICAgICAgaW5pdDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgcGxfc2V0dGluZ3MuaW5pdCgpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIGNvbnN0IHBsX3NldHRpbmdzID0ge1xuICAgICAgICBpbml0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLnVwZGF0ZV9zZXR0aW5ncygpO1xuICAgICAgICB9LFxuICAgICAgICB1cGRhdGVfc2V0dGluZ3M6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGxldCB0aGF0ID0gdGhpcztcbiAgICAgICAgICAgICQoJy5wbC1zd2l0Y2gnKS5saXZlKCdjaGFuZ2UnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICBsZXQgJHRoaXMgPSAkKGUuY3VycmVudFRhcmdldCksXG4gICAgICAgICAgICAgICAgICAgIG5hbWUgPSAkdGhpcy5hdHRyKCduYW1lJyksXG4gICAgICAgICAgICAgICAgICAgIG9wdGlvbl9uYW1lID0gJycsXG4gICAgICAgICAgICAgICAgICAgIG9wdGlvbl92YWx1ZSA9ICgkdGhpcy5wcm9wKFwiY2hlY2tlZFwiKSkgPyAnb24nIDogJ29mZic7XG5cbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGFkbWluX2FqYXhSZXF1ZXN0cy5zd2l0Y2hfc2V0dGluZ3MgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgIGFkbWluX2FqYXhSZXF1ZXN0cy5zd2l0Y2hfc2V0dGluZ3MuYWJvcnQoKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBzd2l0Y2ggKG5hbWUpIHtcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnZW1haWxfY29uZmlybWF0aW9uJzpcbiAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbl9uYW1lID0gJ2VtYWlsX2NvbmZpcm1hdGlvbic7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGF0LnNldHRpbmdzX2FqYXgoJHRoaXMsIG9wdGlvbl9uYW1lLCBvcHRpb25fdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ2xpbWl0X2FjdGl2ZV9sb2dpbic6XG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICdsaW1pdF9hY3RpdmVfbG9naW4nO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG9wdGlvbl92YWx1ZSA9PT0gJ29uJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQoJy5wbC1hY3RpdmUtbG9naW4nKS5mYWRlSW4oKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJCgnLnBsLWFjdGl2ZS1sb2dpbicpLmZhZGVPdXQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIHRoYXQuc2V0dGluZ3NfYWpheCgkdGhpcywgb3B0aW9uX25hbWUsIG9wdGlvbl92YWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnYWRtaW5fYXBwcm92YWwnOlxuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uX25hbWUgPSAnYWRtaW5fYXBwcm92YWwnO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhhdC5zZXR0aW5nc19hamF4KCR0aGlzLCBvcHRpb25fbmFtZSwgb3B0aW9uX3ZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBjYXNlICdjaGVja19rZXl1cCc6XG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICdjaGVja19rZXl1cCc7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGF0LnNldHRpbmdzX2FqYXgoJHRoaXMsIG9wdGlvbl9uYW1lLCBvcHRpb25fdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgJ2Jsb2NrX3VzZXJzJzpcbiAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbl9uYW1lID0gJ2Jsb2NrX3VzZXJzJztcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoYXQuc2V0dGluZ3NfYWpheCgkdGhpcywgb3B0aW9uX25hbWUsIG9wdGlvbl92YWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnbG9naW5fbmV0d29yayc6XG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICdsb2dpbl9uZXR3b3JrJztcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoYXQuc2V0dGluZ3NfYWpheCgkdGhpcywgb3B0aW9uX25hbWUsIG9wdGlvbl92YWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgY2FzZSAnbnVtYmVyX29mX2FjdGl2ZV9sb2dpbic6XG4gICAgICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICdudW1iZXJfb2ZfYWN0aXZlX2xvZ2luJztcbiAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbl92YWx1ZSA9ICR0aGlzLnZhbCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhhdC5zZXR0aW5nc19hamF4KCR0aGlzLCBvcHRpb25fbmFtZSwgb3B0aW9uX3ZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAkKCcucGwtc2V0dGluZy1pbnB1dCcpLmxpdmUoJ2ZvY3Vzb3V0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAgICAgbGV0ICR0aGlzID0gJChlLmN1cnJlbnRUYXJnZXQpLFxuICAgICAgICAgICAgICAgICAgICBuYW1lID0gJHRoaXMuYXR0cignbmFtZScpLFxuICAgICAgICAgICAgICAgICAgICBvcHRpb25fbmFtZSA9ICcnLFxuICAgICAgICAgICAgICAgICAgICBvcHRpb25fdmFsdWUgPSAkdGhpcy52YWwoKTtcblxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgYWRtaW5fYWpheFJlcXVlc3RzLnN3aXRjaF9zZXR0aW5ncyAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICAgICAgYWRtaW5fYWpheFJlcXVlc3RzLnN3aXRjaF9zZXR0aW5ncy5hYm9ydCgpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBzd2l0Y2ggKG5hbWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNhc2UgJ251bWJlcl9vZl9hY3RpdmVfbG9naW4nOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbl9uYW1lID0gJ251bWJlcl9vZl9hY3RpdmVfbG9naW4nO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoYXQuc2V0dGluZ3NfYWpheCgkdGhpcywgb3B0aW9uX25hbWUsIG9wdGlvbl92YWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSwgMjAwKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9LFxuXG4gICAgICAgIHNldHRpbmdzX2FqYXg6IGZ1bmN0aW9uIChlbCwgb3B0aW9uX25hbWUsIG9wdGlvbl92YWx1ZSkge1xuICAgICAgICAgICAgYWRtaW5fYWpheFJlcXVlc3RzLnN3aXRjaF9zZXR0aW5ncyA9ICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdXJsOiBwbF9nbG9iYWxzLmFqYXhVcmwsXG4gICAgICAgICAgICAgICAgdHlwZTogJ1BPU1QnLFxuICAgICAgICAgICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBwbF9nbG9iYWxzLnBsdWdpbl9rZXkgKyAnX3N3aXRjaF9zZXR0aW5ncycsXG4gICAgICAgICAgICAgICAgICAgIG9wdGlvbl9uYW1lOiBvcHRpb25fbmFtZSxcbiAgICAgICAgICAgICAgICAgICAgb3B0aW9uX3ZhbHVlOiBvcHRpb25fdmFsdWUsXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBiZWZvcmVTZW5kOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIGVsLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAocmVzKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChyZXMuc3VjY2Vzcykge1xuXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGNvbXBsZXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIGVsLnByb3AoJ2Rpc2FibGVkJywgZmFsc2UpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgICQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIHBsX2Jhc2ljLmluaXQoKTtcbiAgICB9KTtcblxufSkoalF1ZXJ5KTtcbiJdfQ==
