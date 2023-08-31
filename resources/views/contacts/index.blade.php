<div class="modal-header">
    <h4 class="modal-title">Edit Budget Contact</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body input-width budget-contact-form">
    {!! $form->open(action('ContactController@update', $contact->id)) !!}

    <h1 class="mb-3">{{ $contact->firstname }} {{ $contact->lastname }} </h1>

    <div id="js-budget">
        @input('default_budget_id', ['id' => 'js-budget-id'])
        @inputBlock('budget_search', [
            'label' => 'Default Budget',
            'class' => 'budget-typeahead',
            'data-for' => 'js-budget-id'
        ])
    </div>

    <div id="js-contact">
        @input('fiscal_person_id', ['id' => 'js-contact-id'])
        @inputBlock('contact_search', [
        'label' => 'Finance Manager',
        'class' => 'contact-typeahead',
        'data-for' => 'js-contact-id'
        ])
    </div>

    @input('is_80_20', 'Faculty is 80/20')

    <div class="my-4">
        <button type="submit" class="btn">Save</button>
        <button class="btn js-cancel-modal">Cancel</button>
    </div>

    {!! $form->close() !!}
</div>

