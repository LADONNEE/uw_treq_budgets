@extends('layout/htmlpage')
@section('title', 'Users')
@section('content')

    <h1>Users</h1>

    <p>
        Below are people with user roles in the Budget database. All faculty members listed on the
        <a href="{{ route('faculty-index') }}" target="_blank">Manage Faculty</a> page are automatically granted
        "Viewer" permission in order to approve faculty effort reports. Those faculty members do not need to be added
        below unless they need additional permissions.
    </p>

    <ul class="bullet">
        <li><strong>Viewer</strong> - read-only access</li>
        <li><strong>Fiscal</strong> - can edit budget information</li>
        <li><strong>Admin</strong> - Fiscal + can manage user roles and faculty</li>
        <li><strong>Reconciler</strong> - edit notes, can be assigned as Reconciler</li>
        <li><strong>Budget Manager</strong> - can be assigned as Budget Manager</li>
        <li><strong>Manage Faculty</strong> - can edit faculty information on Manage Faculty page</li>
        <li><strong>Approve on Behalf</strong> - can approve Effort Reports on behalf of budget approvers</li>
        <li><strong>Workday</strong> - can mark Effort Reports "Entered in Workday" on COENV Pay approvals</li>
    </ul>

    <h3>Add a User</h3>

    {!! $form->open(action('UsersController@select')) !!}

    <div class="col-md-4 d-flex pl-0">
        @input('person_id', ['id' => 'js-person-id'])
        @input('person_search', [
            'class' => 'person-typeahead',
            'data-for' => 'js-person-id',
            'placeholder' => 'Search by name or NetID',
        ])
        <input type="submit" value="Add" class="btn" />
    </div>

    {!! $form->close() !!}

    @if (count($users) == 0)

        <div class="emptytable">No users configured. How are you seeing this page?</div>

    @else

        <table class="sortable">
            <thead>
            <tr>
                <th style="width:20%;">Name</th>
                <th style="width:12%;">UW NetID</th>
                <th>Effective Roles</th>
            </tr>
            </thead>

            <tbody>

            @foreach ($users as $user)

                <tr>
                    <td><a href="{!! action('UsersController@edit', [$user->uwnetid]) !!}">{{ eLastFirst($user) }}</a></td>
                    <td><a href="{!! action('UsersController@edit', [$user->uwnetid]) !!}">{{ $user->uwnetid }}</a></td>
                    <td><?php
                        $roles = [];
                        if ($user->hasRole('budget:viewer', $user)) {
                            $roles[] = 'Viewer';
                        }
                        if ($user->hasRole('budget:fiscal', $user)) {
                            $roles[] = 'Fiscal';
                        }
                        if ($user->hasRole('budget:admin', $user)) {
                            $roles[] = 'Admin';
                        }
                        if ($user->hasRole('reconciler', $user)) {
                            $roles[] = 'Reconciler';
                        }
                        if ($user->hasRole('manager', $user)) {
                            $roles[] = 'Budget Manager';
                        }
                        if ($user->hasRole('manage-faculty', $user)) {
                            $roles[] = 'Manage Faculty';
                        }
                        if ($user->hasRole('act-on-behalf', $user)) {
                            $roles[] = 'Approve on Behalf';
                        }
                        if ($user->hasRole('workday', $user)) {
                            $roles[] = 'Workday';
                        }
                        echo e(implode(', ', $roles));
                        ?></td>
                </tr>

            @endforeach

            </tbody>
        </table>

    @endif

@stop
