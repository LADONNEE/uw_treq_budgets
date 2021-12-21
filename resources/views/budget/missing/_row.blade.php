<td><a href="{!! action('BudgetsController@show', $budget->id) !!}">{{ $budget->budgetno }}</a></td>
<td>{{ $budget->name }}</td>
<td>{{ eFirstLast($budget->business) }}</td>
<td class="js-edit-row--trigger pointer">{!! eOrEmpty(eFirstLast($budget->fiscal_person_id)) !!}</td>
<td class="js-edit-row--trigger pointer">{!! eOrEmpty(eFirstLast($budget->reconciler_person_id)) !!}</td>
<td class="js-edit-row--trigger pointer">{!! eOrEmpty($budget->purpose_brief) !!}</td>
<td class="js-edit-row--trigger pointer">{!! eOrEmpty($budget->getFoodPolicy()) !!}</td>
