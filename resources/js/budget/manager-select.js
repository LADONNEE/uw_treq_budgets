import ModalForm from '../common/modal-form';
import AjaxSubmit from '../common/ajax-submit';
import Modal from '../common/modal';

let BudgetManagerSelect = (function($){
    let selectorScope = '.js-has-budget-managers';
    let selectorItem = '.js-budget-manager';

    let openModal = function(e) {
        e.preventDefault();
        ModalForm.get($(e.target).closest(selectorItem).attr('href'), { success: watchForm });
    };

    let watchForm = function(jsModalContent) {
        AjaxSubmit.watch(jsModalContent.find('form'), {
            submitted: function() {
                Modal.hide();
            },
            success: function(data) {
                $('#budget-' + data.budget_id).find(selectorItem).html(data.manager);
            }
        });
    };

    return {
        init: function() {
            $(selectorScope).on('click', selectorItem, openModal);
        }
    };

})(jQuery);

$( document ).ready(function() {
    BudgetManagerSelect.init();
});
