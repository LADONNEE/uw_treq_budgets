
let CommonLinkRow = (function($){
    let hoverClass = 'link-row-hover';

    let hover = function(event) {
        $(event.target).closest('tr').addClass(hoverClass);
    };

    let hoverOff = function(event) {
        $(event.target).closest('tr').removeClass(hoverClass);
    };

    let follow = function(event)
    {
        let href = $(event.target).closest('tr').find('.js-link-row').attr('href');
        if (href) {
            window.location = href;
        }
    };

    return {
        init : function() {
            return $('.js-link-row').closest('tr')
                .addClass('link-row')
                .on('click', follow)
                .on('mouseenter', hover)
                .on('mouseleave', hoverOff);
        }
    };

})(jQuery);
$( document ).ready(function(){
    CommonLinkRow.init();
});

