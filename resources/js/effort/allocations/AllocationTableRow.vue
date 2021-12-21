<template>
    <tr>
        <td v-if="canEdit" class="js-link-row allocation__dates-link" v-on:click="$emit('open', $event, row.id)">
            {{ formatDate(row.start_at) }} - {{ formatDate(row.end_at) }}
        </td>
        <td v-else>
            {{ formatDate(row.start_at) }} - {{ formatDate(row.end_at) }}
        </td>
        <td>{{ row.budget.budgetno }}</td>
        <td v-if="isCrossUnit(row)" class="text-uppercase">{{ row.budget.non_coe_name ? row.budget.non_coe_name : '' }}</td>
        <td v-else class="text-uppercase">{{ row.budget_biennium ? row.budget_biennium.name : '' }}</td>
        <td v-if="isCrossUnit(row) && row.contact.fiscal_person_id">{{ facultyFiscalPerson }}</td>
        <td v-else-if="row.budget && row.budget.fiscal_person_id">{{ row.budget_fiscal_firstname }} {{ row.budget_fiscal_lastname }}</td>
        <td v-else>{{ defaultFiscalPerson }}</td>
        <td>{{ row.pca_code }}</td>
        <td v-if="row.allocation_category" class="text-capitalize">{{ row.allocation_category.toLowerCase() }}</td>
        <td v-else-if="row.additional_pay_category" class="text-uppercase">{{ row.additional_pay_category }}</td>
        <td v-else><span class="empty">missing</span></td>
        <td>{{ row.allocation_percent ? row.allocation_percent + '%' : '$' + row.additional_pay_fixed_monthly}}</td>
    </tr>
</template>
<script>

import AllocationForm from "./AllocationForm";
export default {
    props: ['token', 'canEdit', 'facultyId', 'row', 'defaultFiscalPerson', 'facultyFiscalPerson'],
    methods: {
        isCrossUnit(allocation) {
            return allocation.allocation_category === 'CROSS UNIT EFFORT';
        },
        formatDate(date) {
            const dateParts = date.split('-').map(val => +val);

            return `${dateParts[1]}/${dateParts[2]}/${dateParts[0]}`
        }
    },
    components: {
        AllocationForm,
    }
}
</script>
