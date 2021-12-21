<?php $uwBudget = $uwBudget ?? $budget->uw; ?>
<div class="budget-header">
    <h1 class="budget-header__budgetno">{{ $budget->budgetno }} </h1>
    <div class="budget-header__detail">{{ $budget->name }} @bar {{ $uwBudget->BienniumYear }} Biennium: {{ $uwBudget->getStatusDescription() }}</div>
</div>
