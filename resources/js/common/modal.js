let Modal = (function($){

    let selectorBox = '#_modal';
    let selectorContent = '#_modal_content';

    let setPosition = function(position)
    {
        if (position === 'left') {
            $(selectorBox).removeClass('right');
            $(selectorBox).addClass('left');
        } else if (position === 'right') {
            $(selectorBox).removeClass('left');
            $(selectorBox).addClass('right');
        } else {
            $(selectorBox).removeClass('left right');
        }
    };

    let setSize = function(size)
    {
        if (size === 'lg') {
            $(selectorBox).find('.modal-dialog').addClass('modal-lg');
        } else {
            $(selectorBox).find('.modal-dialog').removeClass('modal-lg');
        }
    };

    return {
        content : function() {
            return $(selectorContent);
        },
        hide : function() {
            return $(selectorBox).modal('hide');
        },
        html : function(content) {
            return $(selectorContent).html(content);
        },
        show : function(position, size) {
            setPosition(position);
            setSize(size);
            return $(selectorBox).modal('show');
        }
    };

})(jQuery);

window.Modal = Modal;

export default Modal;
