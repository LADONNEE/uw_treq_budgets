 function budgetPeopleAdd() {
    $('#js-budget-people-home').hide();
    $('#js-budget-people-add').show();
    $('#js-budget-people-edit').hide();
    $('#js-budget-people-delete').hide();
}
function budgetPeopleEdit(el) {
    let item = $(el);
    let person_id = item.data('personId');
    let name = item.find('.js-name').html();
    let desc = item.find('.js-desc').html();
    $('#js-edit-budget-person-id').val(person_id);
    $('#js-edit-budget-name').html(name);
    $('#js-edit-budget-desc').val(desc);
    $('#js-budget-people-home').hide();
    $('#js-budget-people-add').hide();
    $('#js-budget-people-edit').show();
    $('#js-budget-people-delete').hide();
}
function budgetPeopleDelete(e) {
    e.preventDefault();
    $('#js-budget-people-delete .js-budget-person-id').val($('#js-edit-budget-person-id').val());
    $('#js-budget-people-delete .js-name').html($('#js-edit-budget-name').html());
    $('#js-budget-people-home').hide();
    $('#js-budget-people-add').hide();
    $('#js-budget-people-edit').hide();
    $('#js-budget-people-delete').show();
}
function budgetPeopleCancel(e) {
    e.preventDefault();
    $('#js-budget-people-home').show();
    $('#js-budget-people-add').hide();
    $('#js-budget-people-edit').hide();
    $('#js-budget-people-delete').hide();
}
$( document ).ready(function() {
    $('.js-trigger-budget-person').on('click', function() {
        budgetPeopleEdit(this);
    });
    $('#js-budget-people-home button').on('click', budgetPeopleAdd);
    $('.js-budget-people-cancel').on('click', budgetPeopleCancel);
    $('.js-budget-people-delete').on('click', budgetPeopleDelete);
});
