@extends('layout/htmlpage', ['hasGrid' => true])
@section('title', $budget->budgetno)
@section('content')

    <div class="row">
        <div class="col-md-9">
            <div class="mb-5">
                @if(hasRole('budget:fiscal'))
                    <div class="float-right">
                        <a href="{{ action('BudgetsController@edit', $budget->id) }}">Edit Budget</a>
                    </div>
                @endif

                @include('budget.budgets._header')

                <div class="flex-columns mb-2">
                    <div>
                        <div class="top-label-gray">Spend Authorizer</div>
                        <div class="p-2 text-lg">
                            @if($budget->business)
                                {{ eFirstLast($budget->business) }}
                            @else
                                <span class="empty">Unknown</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="top-label-gray">Budget Manager</div>
                        <div class="p-2 text-lg">
                            {!! eOrEmpty(eFirstLast($budget->manager), 'Unknown') !!}
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="top-label-gray">Reconciler</div>
                        <div class="p-2 text-lg">{!! eOrEmpty(eFirstLast($budget->reconciler), 'Unknown') !!}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="top-label-gray">Budget Purpose</div>
                    @if($budget->purpose_brief)
                        <div class="mt-3 px-2 font-weight-bold">{{ $budget->purpose_brief }}</div>
                    @endif
                    @if($budget->purpose->exists)
                        <div class="p-2 pre-line">{{ $budget->purpose->note }}</div>
                    @else
                        <div class="p-2 empty">
                            No budget purpose recorded.
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <div class="top-label-gray">Food Policy</div>
                    @if($budget->food)
                        <div class="p-2 text-med pre-line">{{ $budget->getFoodPolicy() }}</div>
                    @else
                        <div class="p-2 empty">
                            (Missing)
                        </div>
                    @endif
                    @if($budget->foodNote->exists)
                        <div class="p-2 text-med pre-line">{{ $budget->foodNote->note }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <div class="top-label-gray">People</div>
                    <div class="p-2 mb-3">
                        @if(count($budget->people))

                            <table class="transparent m-0 text-med">
                                <tbody>
                                @foreach($budget->people as $bPerson)

                                <tr class="js-trigger-budget-person pointer" data-person-id="{{ $bPerson->id }}">
                                    <td class="js-name">{{ eFirstLast($bPerson) }}</td>
                                    <td class="js-desc text-gray">{{ $bPerson->description }}</td>
                                </tr>
                                @endforeach

                                </tbody>
                            </table>

                        @else

                            <div class="empty">No people related to this budget.</div>

                        @endif
                    </div>
                    @if(hasRole('budget:fiscal'))
                        @include('budget/budgets/_people-forms')
                    @endif
                </div>


                <div class="mb-3">
                    <div class="top-label-gray">Visible in TREQ Suggestions</div>
                    @if($budget->visible)
                        <div class="p-2 text-med pre-line">Yes</div>
                    @else
                        <div class="p-2 text-med pre-line">No</div>
                    @endif
                </div>


            </div>

            @include('budget.effort.allocations-by-budget._allocations-by-budget')
            @include('budget/budgets/_uw-budget', ['uwBudget' => $uwBudget ?? $budget->uw])

        </div>

        <div class="col-md-3 pt-5">
            @include('components._notes-section', [
                'addNoteUrl' => action('NotesController@create', $budget->id),
                'refreshUrl' => action('NotesController@index', $budget->id),
                'notes' => $budget->notes,
                'editNoteAction' => function($id) {
                    return action('NotesController@edit', $id);
                }
            ])
        </div>
    </div>

@stop
