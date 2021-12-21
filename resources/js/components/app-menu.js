let AppMenu = (function ($) {
    let triggerSelector = '#js-app-menu--trigger';
    let isOpen = false;
    let $menu, $content;

    let start = function () {
        if (!$menu) {
            $menu = $('#js-app-menu');
            $content = $menu.find('.app-menu__content');
            $menu.find('.btn-close').on('click', close);
        }
    };

    let open = function (event) {
        start();
        isOpen = true;
        document.addEventListener('keydown', handleEscape);
        $content.css({right: '100%'});
        $menu.show();
        $content.animate({right: 0}, {duration: 200});
    };

    let close = function (event) {
        if (!$menu || !isOpen) {
            return;
        }
        isOpen = false;
        document.removeEventListener('keydown', handleEscape);
        $content.animate({right: '100%'}, {
            duration: 200,
            complete: function () {
                $menu.hide();
            }
        });
    };

    let toggle = function (event) {
        if (isOpen) {
            close(event);
        } else {
            open(event);
        }
    };

    let handleEscape = function (event) {
        if (isOpen && event.key === 'Escape') {
            event.preventDefault();
            close(event);
        }
    };

    return {
        init: function () {
            $(triggerSelector).on('click', toggle);
        }
    };
})(jQuery);

$(document).ready(function () {
    AppMenu.init();
});
