@if(hasRole('budget:fiscal'))

    {!! $form->open(action('ManagersController@update', $budget->id)) !!}

    @inputBlock('manager')

    <div>
        <button class="btn">Update</button>
    </div>

    {!! $form->close() !!}

@endif
