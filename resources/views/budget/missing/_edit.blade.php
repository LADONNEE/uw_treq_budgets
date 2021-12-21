<div class="modal-header">
    <h4 class="modal-title">Edit Budget</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body input-width">
    {!! $form->open(action('MissingController@update', $budget->id)) !!}

    @include('budget.budgets._header')
    @include('budget.budgets._form-inputs')

    <div class="my-4">
        <button type="submit" class="btn">Save</button>
        <button class="btn js-cancel-modal">Cancel</button>
    </div>

    {!! $form->close() !!}
</div>
