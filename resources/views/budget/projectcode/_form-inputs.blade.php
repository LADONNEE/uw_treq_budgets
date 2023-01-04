@inputBlock('code', 'Project Code')
@inputBlock('description', 'Description')

@inputBlock('allocation_type_frequency', 'Allocation Type / Frequency')
@inputBlock('purpose', 'Purpose')
@inputBlock('pre_approval_required', 'Pre-approval required')
@inputBlock('action_item', 'Action Item')
@inputBlock('workday_code', 'Workday Code')
@inputBlock('workday_description', 'Workday Description')

<div id="js-person">
    @inputBlock('authorizer_person_id', [
    'label' => 'Spend Authorizer',
    'class' => 'person-typeahead',
    'data-for' => 'js-person-id'
    ])

    @inputBlock('fiscal_person_id', [
    'label' => 'Fiscal Person',
    'class' => 'person-typeahead',
    'data-for' => 'js-person-id'
    ])
</div>
