@inputBlock('firstname', 'First Name')
@inputBlock('lastname', 'Last Name')
@inputBlock('uwnetid', 'UW NetID')

<div id="js-contact">
    @input('fiscal_person_id', ['id' => 'js-contact-id'])
    @inputBlock('contact_search', [
    'label' => 'Finance Manager',
    'class' => 'contact-typeahead',
    'data-for' => 'js-contact-id'
    ])
</div>

<div id="js-budget">
    @input('default_budget_id', ['id' => 'js-budget-id'])
    @inputBlock('budget_search', [
    'label' => 'Default Budget',
    'class' => 'budget-typeahead',
    'data-for' => 'js-budget-id'
    ])
</div>

@inputBlock('end_at', [
'label' => 'End Date',
'help' => 'Considered active if blank.',
])

<button type="submit" class="btn btn-primary">Submit</button>
<a class="btn btn-secondary btn-close" href="{{ route('faculty-index') }}">Cancel</a>

