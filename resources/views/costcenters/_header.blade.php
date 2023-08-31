<?php $costcenter = $costcenter ?? $costcenter->id; ?>
<div class="budget-header">
    <h1 class="budget-header__budgetno">{{ $costcenter->workday_code }} </h1>
    <div class="budget-header__detail">{{ $costcenter->name }}</div>
</div>
