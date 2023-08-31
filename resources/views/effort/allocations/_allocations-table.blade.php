<div class="mb-3">
    @if(count($allocations) === 0)
        <div class="empty-table empty">
            No allocations have been created for this faculty member.
        </div>
    @else
        <div class="panel pt-0 pb-0">
            <div class="panel-full-width">
                <table class="table table-hover sortable effort-table">
                    <thead>
                        <tr>
                            <th>Time Period</th>
                            <th>Budget Number</th>
                            <th>Budget Name</th>
                            <th>Budget Manager</th>
                            <th>PCA Code</th>
                            <th>Category</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allocations as $index => $allocation)
                            @if(isset($allocation) && $index > 0)
                                @if($allocation->type != $allocations[$index - 1]->type)
                                    <tr><td colspan="7" class="table-separator"> </td></tr>
                                @endif
                                @if(isset($showAutomaticAllocations) && $allocation->start_at != $allocations[$index - 1]->start_at)
                                    <tr><td colspan="7" class="table-separator"> </td></tr>
                                @endif
                            @endif
                            <tr @if($allocation->is_automatic) class="empty" @endif>
                                <td data-text="{{ eDate($allocation->start_at) }}">
                                    @if(isset($effortReport) || !hasRole('budget:fiscal'))
                                        {{ eDate($allocation->start_at) . ' - ' . eDate($allocation->end_at) }}
                                    @else
                                        <a href="{{ route('allocations-by-faculty-edit', [$allocation, $faculty]) }}" class="js-link-row">
                                            {{ eDate($allocation->start_at) . ' - ' . eDate($allocation->end_at) }}
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $allocation->budget->budgetno ?? '' }}</td>

                                @if($allocation->allocation_category === 'CROSS UNIT EFFORT')
                                    <td>{{ $allocation->budget->non_coe_name ?? '' }}</td>
                                @else
                                    <td>{{ $allocation->budgetBiennium->name ?? '' }}</td>
                                @endif

                                <td>
                                    @if($allocation->allocation_category === 'CROSS UNIT EFFORT' && $faculty->fiscal_person_id)
                                        {{ Str::title($faculty->getFiscalPersonName()) }}
                                    @elseif($allocation->budget && $allocation->budget->manager)
                                        {{ Str::title($allocation->budget->manager->firstname) }} {{ Str::title($allocation->budget->manager->lastname) }}
                                    @elseif($allocation->is_automatic)
                                    @else
                                        {{ Str::title($defaultFiscalPerson) }}
                                    @endif
                                </td>
                                <td>{{ $allocation->pca_code }}</td>
                                @if($allocation->allocation_category)
                                    <td>{{ Str::title($allocation->allocation_category) }}</td>
                                @elseif($allocation->additional_pay_category)
                                    <td>{{ $allocation->additional_pay_category }}</td>
                                @else
                                    <td><span class="empty">missing</span></td>
                                @endif

                                <td>{{  $allocation->allocation_percent ? $allocation->allocation_percent . '%' :
                                    '$' . $allocation->additional_pay_fixed_monthly }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
