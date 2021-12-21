
<div style="clear:both;">
    <h3>Add to Fiscal Team</h3>
    <div style="padding:3px 0;">
    <form method="post" action="{!! action('Budget\FiscalController@store') !!}" class="requires_person_id">
        <input type="text" value="" class="person-typeahead" placeholder="Search by name or NetID" style="width:300px;margin-top:-3px;" />
        <input type="hidden" value="" name="person_id" id="person_id" />
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <input type="submit" value="Add" id="add_fiscal" class="btn" />
    </form>
    </div>
</div>
<!-- hello -->
