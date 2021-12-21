import axios from 'axios';
import Modal from "./modal";
import ContactTypeahead from "../components/contact-typeahead";
import BudgetTypeahead from "../components/budget-typeahead";

let EditRow = (function($){
    let selectorCancel = '.js-cancel-modal';

    let startEdit = function(event) {
        event.preventDefault();
        let $tr = $(event.target).closest('tr');
        let href = $tr.data('href');

        if (href) {
            axios({
                method: 'get',
                url: href
            })
                .then((response) => showForm(response.data))
                .catch(apiError);
        }
    };

    let showForm = function(html) {
        Modal.html(html);
        Modal.show('center', 'med');
        Modal.content().find('form').on('submit', submitAjax);

        $(selectorCancel).on('click', cancelForm);
        ContactTypeahead.init('#_modal .contact-typeahead');
        BudgetTypeahead.init('#_modal .budget-typeahead')
    };

    let cancelForm = function(event) {
        event.preventDefault();
        Modal.hide();
    };

    let submitAjax = function(event) {
        event.preventDefault();
        let $form = $(event.target).closest('form');
        let action = $form.attr('action');
        let data = $form.serialize();

        if (!action || !data) {
            console.log('EditRow.submitAjax() - failed to find form, not submitted')
        }

        axios({
            method: 'post',
            url: action,
            data: data
        })
            .then(showResult)
            .catch(apiError);
    };

    let showResult = function(response) {
        if (response.data.result === 'success') {
            Modal.hide();
            if (response.data.html) {
                $('#' + response.data.id).html(response.data.html);
            } else {
                // for effort default budget, finance manager, and 80/20 field
                $('#' + response.data.defaultBudgetId).html(response.data.defaultBudget);
                $('#' + response.data.financeManagerId).html(response.data.financeManager);
                $('#' + response.data.is8020Id).html(response.data.is8020);
            }
        } else {
            showForm(response.data.html);
        }
    };

    let apiError = function(error) {
        console.log('EditRow.apiError()');
        console.log(error.response);
        console.log(error);
    }

    return {
        init: function(){
            $('.js-edit-row').on('click', '.js-edit-row--trigger', startEdit);
        }
    };
})($);

$( document ).ready(function(){
    EditRow.init();
});
