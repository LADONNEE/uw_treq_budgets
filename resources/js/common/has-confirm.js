let HasConfirm = (function($){
    let confirm = function(event) {
        event.preventDefault();
        let container = $(event.target).closest('.js-has-confirm');
        container.find('.js-home-view').hide();
        container.find('.js-confirm-view').show();
    };
    let cancel = function(event) {
        event.preventDefault();
        let container = $(event.target).closest('.js-has-confirm');
        container.find('.js-confirm-view').hide();
        container.find('.js-home-view').show();
    };
    return {
        init: function(){
            $('.js-has-confirm .js-show-confirm').on('click', confirm);
            $('.js-has-confirm .js-cancel').on('click', cancel);
        }
    };
})($);

$( document ).ready(function(){
    HasConfirm.init();
});
