@extends('layout/htmlpage')
@section('title', 'User ' . eFirstLast($user))
@section('content')

    <h1>User Roles</h1>

    <h2>{{ $user->firstname }} {{ $user->lastname }}</h2>

    @if($user->hasRole('budget:super'))

        <p style="color:#006400;font-style:italic;">This user has system level super user role and inherits all permissions.</p>

    @endif

    {!! $form->open(action('UsersController@update', $user->uwnetid)) !!}

    <div class="d-flex">
        <div class="col-md-6">
            <h3 class="mb-3">User Roles</h3>
            <div class="help">
                @input('role')

                <div id="budget:viewer" style="display: none">
                    <ul class="pl-3">
                        <li>Read-only view of budgets database</li>
                    </ul>
                </div>

                <div id="budget:fiscal" style="display: none">
                    <ul class="pl-3">
                        <li>Assign Budget Manager to budgets</li>
                        <li>Assign Reconciler to budgets</li>
                        <li>Edit budgets, set Purpose, PI</li>
                    </ul>
                </div>

                <div id="budget:admin" style="display: none">
                    <ul class="pl-3">
                        <li>Permissions of Fiscal plus...</li>
                        <li>Manage budget users</li>
                        <li>Manage faculty list</li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="col-md-6 add-on-permissions">
            <h3 class="mb-3">Add-On Permissions</h3>

            <div class="add-on-div">
                @input('reconciler', 'Reconciler')
                @input('manager', 'Manager')
                @input('manage-faculty', 'Manage Faculty')
                @input('act-on-behalf', 'Act on Behalf')
                @input('workday', 'Workday')
            </div>

            <div id="reconciler" style="display: none">
                <ul class="pl-3">
                    <li>Can add Notes to budgets and edit Notes</li>
                    <li>Can be assigned to a budget as <strong>Reconciler</strong></li>
                </ul>
            </div>

            <div id="manager" style="display: none">
                <ul class="pl-3">
                    <li>Can be assigned to a budget as <strong>Budget Manager</strong></li>
                </ul>
            </div>

            <div id="manage-faculty" style="display: none">
                <ul>
                    <li class="pl-3">Add and Edit faculty on the Manage Faculty page</li>
                </ul>
            </div>

            <div id="act-on-behalf" style="display: none">
                <ul>
                    <li class="pl-3">
                        Allows user to approve on behalf of budget approvers on Faculty Effort Reports
                    </li>
                </ul>
            </div>

            <div id="workday" style="display: none">
                <ul>
                    <li class="pl-3">
                        Gives user access to the "Entered in Workday" button on the COE Pay approvals on Faculty Effort Reports.
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="submitbox">
        <button type="submit" class="btn">Save</button>
    </div>

    {!! $form->close() !!}

@stop
@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            if ($('input:checked').val() === '') {
                $('.add-on-permissions').hide();
            }

            // Roles description text
            $('input[name=role]').each(function () {
                let inputId = $(this).attr('id');
                let role = $(this).val();

                $(`[id="${inputId}"]`).closest('.form-check').append($(`[id="${role}"]`).clone().show());
            });

            // Add-on permissions description text
            $('div.add-on-div input[type=checkbox]').each(function () {
                let inputId = $(this).attr('id');
                let addOn = $(this).attr('name');

                $(`[id="${inputId}"]`).closest('.form-check').append($(`[id="${addOn}"]`).clone().show());
            });

            $('input[name=role]').change(function () {
                if ($('input:checked').val() === '') {
                    $('.add-on-permissions').hide();
                } else {
                    $('.add-on-permissions').show();
                }
            })

        });
    </script>
@stop
