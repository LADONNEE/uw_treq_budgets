let AjaxSubmit = (function($){

    let watch = function(selector, options) {
        options = options || {};
        options.dataType  = options.hasOwnProperty('dataType ') ? options.dataType  : 'json';
        options.submitted = options.hasOwnProperty('submitted') ? options.submitted : submittedNull;
        options.success = options.hasOwnProperty('success') ? options.success : successNull;
        options.error = options.hasOwnProperty('error') ? options.error : showError;

        $(selector).on('submit', function(e) {
            e.preventDefault();
            submitForm($(e.target).closest('form'), options);
        });
    };

    let submitForm = function(jqForm, options) {
        if (!jqForm.is('form')) {
            console.log('AjaxSubmit.submitForm() - argument is not a form');
            return;
        }
        $.ajax({
            dataType: options.dataType,
            url: jqForm.attr('action'),
            method: jqForm.attr('method') || 'POST',
            data: jqForm.serialize(),
            success: function(data, textStatus, jqXHR){
                options.success(data, jqForm, options);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                options.error(jqXHR, textStatus, errorThrown);
            }
        });
        options.submitted(jqForm);
    };

    let submittedNull = function(jqForm) {
        // do nothing
    };

    let successNull = function(data, jqForm) {
        // do nothing
    };

    let showError = function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        console.log(errorThrown);
    };

    return {
        watch: watch
    };

})(jQuery);

export default AjaxSubmit;
