
<div class="page_menu dropdown pull-right">
    <a data-toggle="dropdown">Staff @icon('ellipsis-v')</a>
    <ul class="dropdown-menu">
        <li><a href="{{ action('FiscalController@edit', $person->person_id) }}">Contact Info</a></li>
        <li><a data-target="remove_fiscal_form" class="show_inline_form" href="javascript:void(0);">Remove from fiscal team</a></li>
    </ul>
</div>

<div class="inline_form_box" id="remove_fiscal_form" style="display:none;">
    <h3>Remove {{ eFirstLast($person) }}</h3>
    <br>

    <div class="alert alert-danger">Remove {{ eFirstLast($person) }} from the fiscal team? Action will remove this
        person as a budget contact from all budgets.</div>

    <form method="post" action="{!! action('FiscalController@destroy', $person->person_id) !!}">
        <input type="hidden" name="person_id" value="{{ $person->person_id }}">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <input type="submit" value="Confirm Remove" class="btn" />
    </form>
    <hr>
</div>

