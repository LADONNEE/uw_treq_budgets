<div class="pb-4">
    <h3>Additional Pay</h3>

    @if(count($allocations) === 0 || ! $typeAdditionalPay = \App\Models\EffortReportAllocation::getAllocationsByType($allocations, 'ADDITIONAL PAY'))
        <div class="empty-table">
            none
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
                        <th>Amt/Month</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($typeAdditionalPay as $allocation)
                        <tr>
                            <td data-text="{{ eDate($allocation->start_at) }}">
                                @if(isset($effortReport))
                                    {{ eDate($allocation->start_at) . ' - ' . eDate($allocation->end_at) }}
                                @else
                                    <a href="{{ route('allocations-by-faculty-edit', [$allocation, $faculty]) }}"
                                       class="js-link-row">
                                        {{ eDate($allocation->start_at) . ' - ' . eDate($allocation->end_at) }}
                                    </a>
                                @endif
                            </td>
                            <td>{{ $allocation->budget->budgetno }}</td>
                            <td>{{ $allocation->budgetBiennium->name }}</td>
                            <td>
                                @if($allocation->budget->manager)
                                    {{ Str::title($allocation->budget->manager->firstname) }} {{ Str::title($allocation->budget->manager->lastname) }}
                                @endif
                            </td>
                            <td>{{ $allocation->pca_code }}</td>
                            <td>{{ $allocation->additional_pay_category }}</td>
                            <td>{{ '$' . $allocation->additional_pay_fixed_monthly }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
