import { suggestions } from '../suggestions/suggestion-factory';

let PersonTypeahead = (function($){
    let classPersonChanging = 'person-changing';
    let classPersonEmpty = 'person-empty';
    let classPersonSelected = 'person-selected';
    let bhSource = null;

    let storeOriginalValue = function(typeaheads)
    {
        typeaheads.each(function(index){
            let typeahead = $(this);
            let personId = getValueInput(this).val();
            if (personId) {
                typeahead = $(typeahead);
                typeahead.data('selected-name', typeahead.val());
                typeahead.data('original', {
                    "id": personId,
                    "name": typeahead.val()
                });
            }
        });
    };

    let getValueInput = function(typeahead)
    {
        // typeahead's data-for="" attribute, refers to value input's ID
        let selector = '#' + $(typeahead).data('for');
        if (selector === '#undefined') {
            // default name for legacy person select inputs
            return $('#person_id');
        }
        return $(selector);
    };

    let onChanging = function(event)
    {
        let typeahead = $(this);
        if (typeahead.val() !== typeahead.data('selected-name')) {
            getValueInput(typeahead).val('');
            typeahead.data('selected-name', null);
            typeahead.removeClass(classPersonEmpty);
            typeahead.removeClass(classPersonSelected);
            typeahead.addClass(classPersonChanging);
        }
    };

    let onKeyPress = function(event) {
        // catch Enter (13) events and re-trigger as Tab (9)
        if (event.which === 13) {
            event.preventDefault();
            let e = jQuery.Event("keydown");
            e.keyCode = e.which = 9;
            $(this).trigger(e);
        }
        // Escape key reverts to original value
        if (event.which === 27) {
            revert(this);
        }
    };

    let onSelected = function(event, option)
    {
        let typeahead = $(this);
        getValueInput(typeahead).val(option.person_id);
        typeahead.data('selected-name', typeahead.val());
        typeahead.removeClass(classPersonChanging);
        typeahead.removeClass(classPersonEmpty);
        typeahead.addClass(classPersonSelected);
        typeahead.typeahead('close');
        let cb = typeahead.data('typeaheadSelectedCallback');
        if (typeof cb === 'function') {
            cb(option);
        }
    };

    let revert = function(typeahead)
    {
        typeahead = $(typeahead);
        let original = typeahead.data('original');
        if (original) {
            typeahead.typeahead('val', original.name);
            getValueInput(typeahead).val(original.id);
            typeahead.data('selected-name', original.name);
            typeahead.removeClass(classPersonChanging);
            typeahead.removeClass(classPersonEmpty);
            typeahead.addClass(classPersonSelected);
        }
    };

    let setup = function(elem, selectedCallback)
    {
        if (elem.length === 0 || elem.data('typeaheadStarted')) {
            return;
        }
        bhSource = suggestions('person');
        elem.typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'people',
                source: bhSource,
                display: function(option) {
                    return option.firstname + ' ' +  option.lastname + ' (' + option.uwnetid + ')';
                }
            })
            .on('keydown', onKeyPress)
            .on('keyup', onChanging)
            .on('typeahead:change', onChanging)
            .on('typeahead:selected typeahead:autocompleted', onSelected)
            .data('typeaheadSelectedCallback', selectedCallback)
            .data('typeaheadStarted', true);
        storeOriginalValue(elem);
        return elem;
    };

    return {
        init: function(selector, selectedCallback) {
            return setup($(selector), selectedCallback);
        }
    }
})(jQuery);

$( document ).ready(function() {
    PersonTypeahead.init('input.person-typeahead');
});

export default PersonTypeahead;
