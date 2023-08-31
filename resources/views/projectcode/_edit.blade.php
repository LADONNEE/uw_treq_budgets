<div class="modal-header">
    <h4 class="modal-title">Edit Project Code</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body input-width">
    {!! $form->open(action('ProjectCodeController@update', $projectcode->id)) !!}

    @include('budget.projectcode._header')
    @include('budget.projectcode._form-inputs')

    <div class="my-4">
        <button type="submit" class="btn">Save</button>
        <button class="btn js-cancel-modal">Cancel</button>
    </div>

    {!! $form->close() !!}
</div>
