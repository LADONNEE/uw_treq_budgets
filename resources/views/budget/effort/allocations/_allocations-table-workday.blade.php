<div class="mb-3">
    @if(count($allocations) === 0 || !$allocationsWithDefaults)
        <div class="empty-table">
            none
        </div>
    @else
        <div class="panel pt-0 pb-0">
            <div class="panel-full-width">
                <table class="table table-hover sortable effort-table allocations-table-with-defaults">
                    <thead>
                    <tr>
                        <th>Time Period</th>
                        <th>Budget Number</th>
                        <th>Budget Name</th>
                        <th>Budget Manager</th>
                        <th>PCA Code</th>
                        <th>Category</th>
                        <th>% Effort</th>
                        @if(isset($showSplit) && $showSplit)
                        <th>Split</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    <?php $fte = 0 ?>
                    @foreach ($niceAllocationsWithCostingTableData as $index => $allocation)
                        <?php
                        $allocationIsAutomatic = $allocation->category === App\Models\EffortReportAllocation::TYPE_DEFAULT
                            || $allocation->category === App\Models\EffortReportAllocation::TYPE_HIATUS
                            || $allocation->category === \App\Models\EffortReportAllocation::TYPE_UNPAID;
                        $isFteRow = $allocation->category === '' && $allocation->budgetno === '';
                        ?>
                        @if (isset($effortReport) && $index > 0 && $allocation->calculated != $niceAllocationsWithCostingTableData[$index - 1]->calculated)
                            <tr><td colspan="8" class="table-separator"> </td></tr>
                        @endif
                        <tr style="{{ $isFteRow ? "background-color: #f0f8ff; font-weight: bold"
                            : ($allocationIsAutomatic ? "color: #777;" : '')}}">
                            <td>{{ $allocation->period }}</td>
                            <td>{{ $allocation->budgetno }}</td>
                            <td>{{ $allocation->budgetName }}</td>
                            <td>{{ Str::title($allocation->budgetManager) }}</td>
                            <td>{{ $allocation->pca }}</td>
                            <td>{{ Str::title($allocation->category) }}</td>
                            <td>
                                {{ $allocation->effort }}%
                                @if($isFteRow)
                                    FTE
                                @endif
                            </td>
                            @if(isset($showSplit) && $showSplit)
                            <td>{{ $allocation->split }}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
