<div class="data_card">
    <h2 class="mb-3">Faculty Effort Allocations</h2>

    @if(count($allocations) === 0)
        <div class="empty-table empty mb-5">
            No allocations have been created for this budget number.
        </div>
    @else
        <table class="mb-5" style="width:100%;">
            <tr class="top_headings">
                <th>Time Period</th>
                <th>Faculty</th>
                <th>PCA Code</th>
                <th>Category</th>
                <th>Amount</th>
            </tr>

            @foreach($allocations as $allocation)
                <tr class="allocation" data-id="{{ $allocation->id }}" data-budget-id="{{ $allocation->budget_id }}">
                    <td>{{ eDate($allocation->start_at) }} - {{ eDate($allocation->end_at) }}</td>
                    <td>{{ $allocation->contact->firstname }} {{ $allocation->contact->lastname }}</td>
                    <td>{{ $allocation->pca_code }}</td>
                    <td>{{ $allocation->allocation_category ? Str::title($allocation->allocation_category) : $allocation->additional_pay_category }}</td>
                    <td>{{ $allocation->allocation_percent ? $allocation->allocation_percent . '%' : '$' . $allocation->additional_pay_fixed_monthly }}</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
