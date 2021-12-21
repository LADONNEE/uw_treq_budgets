import Modal from './modal';

let ModalForm = (function($){
    let selectorCancel = '.js-cancel-modal';

    let requestContent = function(url, options) {
        options = options || {};
        options.position = options.hasOwnProperty('position') ? options.position : '';
        options.size = options.hasOwnProperty('size') ? options.size : '';
        options.success = options.hasOwnProperty('success') ? options.success : successNull;
        options.error = options.hasOwnProperty('error') ? options.error : showError;
        $.ajax({
            async: true,
            dataType: "html",
            url: url,
            success: function(data, textStatus, jqXHR){
                showContent(data, options);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                options.error(jqXHR, textStatus, errorThrown);
            }
        });
    };

    let showContent = function(content, options) {
        Modal.html(content);
        Modal.show(options.position, options.size);
        $(selectorCancel).on('click', cancelForm);
        options.success(Modal.content());
    };

    let cancelForm = function(event) {
        event.preventDefault();
        Modal.hide();
    };

    let successNull = function(jq) {
        // do nothing
    };

    let showError = function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        console.log(errorThrown);
    };

    return {
        get: requestContent
    };

})(jQuery);

export default ModalForm;
