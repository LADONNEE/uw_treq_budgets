let SearchBar = (function($){
    const triggerSelector = '.js-search-show';
    const searchBarSelector = '#search-bar';

    let toggle = function(event) {
        if ( $(searchBarSelector).is( ":hidden" ) ) {
            show();
        } else {
            hide();
        }
    };

    let hide = function() {
        $('#coe-nav-collapse-1').collapse('hide');
        $(searchBarSelector).slideUp( 300, function() {
            // Animation complete.
        });
    };

    let show = function() {
        $('#coe-nav-collapse-1').collapse('hide');
        $(searchBarSelector).slideDown( 300, function() {
            $(searchBarSelector).find('.search-input').focus();
        });
    };

    return {
        init : function()
        {
            let bar = $(searchBarSelector);
            if (bar.length === 0 || bar.children().length === 0) {
                return;
            }
            $(triggerSelector).on('click', toggle)
                .parent().show();
            $('.js-search-close').on('click', hide);
        }
    };
})(jQuery);

$( document ).ready(function() {
    SearchBar.init();
});
