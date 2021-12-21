let BudgetSettings = (function($){
    let showSettings = function(event) {
        let row = $(event.target).closest('tr');
        $('#js-setting-label').html(row.data('name'));
        $('#js-setting-name').val(row.data('name'));
        $('#js-setting-value').val(row.data('value'));
        $('#js-setting-form').show();
    };

    let hideSettings = function(event) {
        event.preventDefault();
        $('#js-setting-form').hide();
    };

    return {
        init: function() {
            $('.js-settings-start').on('click', showSettings);
            $('#js-setting-cancel').on('click', hideSettings);
        }
    };
})(jQuery);

$( document ).ready(function() {
    BudgetSettings.init();
});
