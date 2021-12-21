let NoteAdd = (function($){
    let selCancel  = '.js-note-add-cancel';
    let selContainer = '.js-note-section';
    let selFormBox = '.note-form-box';
    let selFormTrigger = '.note-trigger';
    let selLinkGetForm = '.js-note-add';
    let selMockInput = '.js-note-add-alt';
    let selNoteList = '.js-note-items';

    let closestContainer = function(ref) {
        return $(ref).closest(selContainer)
    };

    let requestForm = function(event) {
        event.preventDefault();
        let container = closestContainer(event.target);
        let formLink = container.find(selLinkGetForm);
        $.ajax({
            dataType: "html",
            url: formLink.attr('href'),
            success: function(data, textStatus, jqXHR) {
                showForm(container, data);
            },
            error: showError
        });
        container.find(selFormTrigger).hide();
    };

    let showError = function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
    };

    let showForm = function(container, data) {
        let formBox = container.find(selFormBox);
        formBox.html(data).show();
        formBox.find("form").on('submit', submitForm);
        formBox.find("textarea").focus();
    };

    let cancelForm = function(event) {
        event.preventDefault();
        let container = closestContainer(event.target);
        container.find(selFormBox).hide();
        container.find(selFormTrigger).show();
    };

    let submitForm = function(event) {
        event.preventDefault();
        let form = $(event.target);
        let container = closestContainer(event.target);
        $.ajax({
            dataType : "json",
            type     : form.attr('method'),
            url      : form.attr('action'),
            data     : form.serialize(),
            success: function(data, textStatus, jqXHR) {
                requestRefresh(container);
            }
        });
        container.find(selFormBox).hide();
        container.find(selFormTrigger).show();
    };

    let requestRefresh = function(container) {
        let href = container.data('refresh');
        if (!href) {
            return;
        }
        $.ajax({
            dataType: "html",
            url: href,
            success: function(data, textStatus, jqXHR) {
                updateRefresh(container, data);
            }
        });
    };

    let updateRefresh = function(container, data) {
        $(container).find(selNoteList).html(data);
    };

    let initMockInput = function(container) {
        if (container === undefined) {
            container = selContainer;
        }
        $(container + ' ' + selLinkGetForm).hide();
        $(container + ' ' + selMockInput).show();
    };

    return {
        init: function(container) {
            if (container === undefined) {
                container = selContainer;
            }
            initMockInput(container);
            $(container).on('focus', selMockInput, requestForm);
            $(container).on('click', selCancel, cancelForm);
        }
    };
})(jQuery);

$( document ).ready(function() {
    NoteAdd.init();
});

export default NoteAdd;
