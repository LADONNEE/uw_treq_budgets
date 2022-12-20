<div id="js-person">
    @input('business_person_id', ['id' => 'js-person-id'])
    @inputBlock('person_search', [
    'label' => 'Spend Authorizer',
    'class' => 'person-typeahead',
    'data-for' => 'js-person-id'
    ])
</div>

@inputBlock('fiscal_person_id', 'Budget Manager')
@inputBlock('reconciler_person_id', 'Reconciler')
@inputBlock('purpose_brief', 'Purpose Brief')
@inputBlock('purpose', [
'label' => 'Budget Purpose',
'rows' => 6,
])
@input('people_data')
@inputBlock('food', 'Food')
@inputBlock('food_note', [
'label' => 'Food Note',
'rows' => 3,
])
@inputBlock('visible', 'Visible in TREQ Budget suggestions')