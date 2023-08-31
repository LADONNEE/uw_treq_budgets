<div class="mb-4">
    <div id="js-budget-people-home"><button class="btn">&plus; Person</button></div>

    <div id="js-budget-people-add" class="border rounded bg-light p-3 input_row" style="display:none;">
        <form action="{{ action('BudgetPersonController@store', $budget->id) }}" method="post">
            {!! csrf_field() !!}
            <div class="inputouterbox input-width" style="width: 300px;">
                <div class="labelbox"><label>Person</label></div>
                <div class="inputbox">
                    <input type="hidden" value="" name="person_id" id="budget_person_id" />
                    <input type="text" id="budget_person_id_typeahead" name="budget_person_id_typeahead" class="person-typeahead" placeholder="Search by name or NetID" value="" data-for="budget_person_id"  />
                </div>
            </div>
            <div class="inputouterbox input-width" style="width: 300px;">
                <div class="labelbox"><label for="add_budget_person_desc">Description</label></div>
                <div class="inputbox">
                    <input type="text" id="add_budget_person_desc" name="description" />
                </div>
            </div>
            <div class="inputouterbox">
                <div class="labelbox">&nbsp;</div>
                <div class="inputbox" style="position:relative; top:3px;">
                    <button class="btn">Add</button>
                    <button class="btn js-budget-people-cancel">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    <div id="js-budget-people-edit" class="border rounded bg-light p-3 input_row" style="display:none;">
        <form action="{{ action('BudgetPersonController@update') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" value="" name="budget_person_id" id="js-edit-budget-person-id" />
            <div class="inputouterbox input-width" style="width: 300px;">
                <div class="labelbox"><label for="js-edit-budget-name">Person</label></div>
                <div class="inputbox">
                    <div class="read_only_input" id="js-edit-budget-name"></div>
                </div>
            </div>
            <div class="inputouterbox input-width" style="width: 300px;">
                <div class="labelbox"><label for="js-edit-budget-desc">Description</label></div>
                <div class="inputbox">
                    <input type="text" id="js-edit-budget-desc" name="description" />
                </div>
            </div>
            <div class="inputouterbox">
                <div class="labelbox">&nbsp;</div>
                <div class="inputbox" style="position:relative; top:3px;">
                    <button class="btn">Save</button>
                    <button class="btn js-budget-people-delete">Delete...</button>
                    <button class="btn js-budget-people-cancel">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    <div id="js-budget-people-delete" class="border rounded bg-light p-3 input_row" style="display:none;">
        <form action="{{ action('BudgetPersonController@update') }}" method="post">
            {!! csrf_field() !!}
            <div class="text-danger mb-2">
                Confirm remove <span class="js-name"></span> from budget {{ $budget->budgetno }}
            </div>
            <div>
                <input type="hidden" value="" name="budget_person_id" class="js-budget-person-id" />
                <input type="hidden" name="action" value="delete">
                <button class="btn">Confirm Delete</button>
                <button class="btn js-budget-people-cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

