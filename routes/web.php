<?php

$route = app('router');
if (!$route instanceof Illuminate\Routing\Router) {
    exit;
}

$route->get('/', 'BudgetsController@index')->name('home');

$route->get('home', 'HomeController@index');
$route->get('about', 'AboutController@index');
$route->get('biennium/{biennium}', 'BudgetsController@biennium');

$route->get('{budget}/edit', 'BudgetsController@edit');
$route->post('{budget}/edit', 'BudgetsController@update');

$route->post('{budget}/person', 'BudgetPersonController@store');
$route->post('budget-person', 'BudgetPersonController@update');

$route->get('{budget}/manager', 'ManagersController@edit');
$route->post('{budget}/manager', 'ManagersController@update');

$route->get('missing', 'MissingController@index');
$route->get('{budget}/missing', 'MissingController@edit');
$route->post('{budget}/missing', 'MissingController@update');

$route->get('{budget}/notes', 'NotesController@index');
$route->get('{budget}/note', 'NotesController@create');
$route->post('{budget}/note', 'NotesController@store');
$route->get('/note/{note}', 'NotesController@edit');
$route->post('/note/{note}', 'NotesController@update');

$route->get('scope', 'ScopeController@index');

$route->get('search', 'SearchController@index');

$route->get('settings', 'SettingsController@index');
$route->post('settings', 'SettingsController@store');

$route->get('pending-email', 'PendingEmail')->name('pending-email');

$route->get('users', 'UsersController@index');
$route->post('users/select', 'UsersController@select');
$route->get('user', 'UsersController@create');
$route->post('user', 'UsersController@store');
$route->get('user/{uwnetid}', 'UsersController@edit');
$route->post('user/{uwnetid}', 'UsersController@update');

$route->get('faculty', 'FacultyController@index')->name('faculty-index');
$route->get('faculty/create', 'FacultyController@create')->name('faculty-create');
$route->post('faculty', 'FacultyController@store')->name('faculty-store');
$route->get('faculty/edit/{faculty}', 'FacultyController@edit')->name('faculty-edit');
$route->post('faculty/update/{faculty}', 'FacultyController@update')->name('faculty-update');

$route->get('api/faculty', 'FacultyApiController@index');
$route->get('api/faculty/{faculty}/allocations', 'AllocationsApiController@index');
$route->get('api/people', 'PeopleApiController@index');
$route->get('api/budgets', 'BudgetApiController@prefetch');

$route->get('contact', 'ContactController@index');
$route->get('{contact}/contact', 'ContactController@edit');
$route->post('{contact}/contact', 'ContactController@update');

$route->get('effort/', 'EffortController@index')->name('effort-home');

$route->get('effort/reports/approvedAllocations', 'ReportApprovedAllocationsController@index')->name('report-effort-allocations');
$route->get('effort/reports/summerHiatus', 'ReportSummerHiatusController@index')->name('report-summer-hiatus');

$route->get('effort/api/notes/{effortReport}/{section?}', 'EffortReportNotesApiController@index');
$route->post('effort/api/notes/{effortReport}/{section}', 'EffortReportNotesApiController@store');
$route->post('effort/api/notes/{note}', 'EffortReportNotesApiController@update');

$route->get('effort/allocations/faculty/{faculty}', 'AllocationsByFacultyController@index')->name('allocations-by-faculty');
$route->get('effort/allocations/faculty/{faculty}/create', 'AllocationsApiController@create')->name('allocations-by-faculty-create');
$route->post('effort/allocations/faculty/{faculty}', 'AllocationsApiController@store')->name('allocations-by-faculty-store');
$route->get('effort/allocations/{allocation}/faculty/{faculty}/edit', 'AllocationsApiController@edit')->name('allocations-by-faculty-edit');
$route->post('effort/allocations/{allocation}/faculty/{faculty}', 'AllocationsApiController@update')->name('allocations-by-faculty-update');

$route->get('effort/allocations/{allocation}/budget/{budget}/edit', 'AllocationsByBudgetController@edit')->name('allocations-by-budget-edit');
$route->post('effort/allocations/{allocation}/budget/{budget}', 'AllocationsByBudgetController@update')->name('allocations-by-budget-update');

$route->get('effort/faculty/{faculty}/report', 'EffortReportController@index')->name('effort-report');
$route->get('effort/faculty/{faculty}/report/create', 'EffortReportController@create')->name('effort-report-create');
$route->post('effort/faculty/{faculty}/report', 'EffortReportController@store')->name('effort-report-store');
$route->get('effort/faculty/{faculty}/report/{effortReport}', 'EffortReportController@show')->name('effort-report-show');
$route->get('effort/faculty/{faculty}/report/{effortReport}/edit', 'EffortReportController@edit')->name('effort-report-edit');
$route->post('effort/faculty/{faculty}/report/{effortReport}', 'EffortReportController@update')->name('effort-report-update');

$route->get('effort/cancel/{effortReport}', 'CancelController@edit')->name('cancel-report');
$route->post('effort/cancel/{effortReport}', 'CancelController@update');

$route->get('api/{effortReport}/approvals', 'ApprovalController')->name('approvals');
$route->post('api/{effortReport}/approvals', 'UpdateApproval')->name('approvals-update');

$route->get('whoami', 'WhoamiController@index');
$route->post('whoami', 'WhoamiController@update');
$route->get('logout', 'WhoamiController@logout');

// Must be last
$route->get('{budget}', 'BudgetsController@show')->name('budget-details');

