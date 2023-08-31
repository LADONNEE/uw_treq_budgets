<div class="data_card">
    <h2 class="mb-3">UW Budget Information</h2>
    <table style="width:100%;">
        <tr class="top_headings">
            <th style="width:33%;">Biennium Year</th>
            <th colspan="2">Status</th>
        </tr>
        <tr>
            <td>{{ $uwBudget->BienniumYear }}</td>
            <td colspan="2">{{ $uwBudget->BudgetStatus }} {{ $uwBudget->getStatusDescription() }}</td>
        </tr>
        <tr class="top_headings">
            <th style="width:33%;">UW OrgCode</th>
            <th style="width:33%;">Payroll unit code</th>
            <th>Principal Investigator</th>
        </tr>
        <tr>
            <td>{{ $uwBudget->OrgCode }}</td>
            <td>{{ $uwBudget->PayrollUnitCode }}</td>
            <td>{{ $uwBudget->PrincipalInvestigator }}</td>
        </tr>
        <tr class="top_headings">
            <th>Begin</th>
            <th>End</th>
            <th>Indirect cost rate</th>
        </tr>
        <tr>
            <td>{{ eDate($uwBudget->getBeginDate()) }}</td>
            <td>{{ eDate($uwBudget->getEndDate()) }}</td>
            <td>{{ $uwBudget->AccountingIndirectCostRate }}</td>
        </tr>
        <tr class="top_headings">
            <th colspan="3">Budget type</th>
        </tr>
        <tr>
            <td colspan="3">{{ $uwBudget->BudgetType }} {{ $uwBudget->getTypeDescription() }}</td>
        </tr>
        <tr class="top_headings">
            <th colspan="3">Budget class</th>
        </tr>
        <tr>
            <td colspan="3">{{ $uwBudget->BudgetClass }} {{ $uwBudget->BudgetClassDesc }}</td>
        </tr>
    </table>
</div>
