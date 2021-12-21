<template>
    <div class="mb-3">
        <div class="empty-table empty" v-if="isEmptyTable">
            No allocations have been created for this faculty member.
        </div>

        <div class="panel pt-0 pb-0" v-if="!isEmptyTable">
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
                        <template v-for="(row, i) in tableData.data">
                            <tr v-if="i > 0 && row.type !== tableData.data[i-1].type">
                                <td colspan="7" class="table-separator"></td>
                            </tr>

                            <allocation-table-row
                                :row="row"
                                :faculty-id="facultyId"
                                :can-edit="canEdit"
                                :token="token"
                                v-on:open="openForm"
                                :class="'allocation__table-row-' + row.id"
                                :default-fiscal-person="tableData.default_fiscal_person"
                                :faculty-fiscal-person="tableData.faculty_fiscal_person"
                            ></allocation-table-row>
                            <tr v-if="selectedAllocation === row.id">
                                <td colspan="7">
                                    <allocation-form
                                        :submit-url="`/budgets/effort/allocations/${selectedAllocation}/faculty/${facultyId}`"
                                        task="edit"
                                        :allocation-id="selectedAllocation"
                                        :faculty-id="facultyId"
                                        v-on:close="close"
                                        :token="token"
                                        :class="'allocation__form-' + row.id"
                                        @dataUpdated="update"
                                    ></allocation-form>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>

import AllocationForm from "./AllocationForm";
import AllocationTableRow from "./AllocationTableRow";
export default {
    props: ['token', 'tableData', 'canEdit', 'facultyId'],
    data () {
        return {
            selectedAllocation: null,
            hasAdditionalPay: false
        };
    },
    computed: {
        isEmptyTable() {
            if (this.tableData.data) {
                return this.tableData.data.length <= 0;
            }
        }
    },
    watch: {
        isEmptyTable() {
            $('#new-snapshot-button').toggle(!this.isEmptyTable);
        }
    },
    methods: {
        openForm(event, allocationId) {
            this.selectedAllocation = allocationId;
        },
        close() {
            this.selectedAllocation = null;
        },
        isCrossUnit(allocation) {
            return allocation.allocation_category === 'CROSS UNIT EFFORT';
        },
        update() {
            this.$emit('dataUpdated')
        },
        isFirstAdditionalPayRow(row) {
            console.log(row.type === 'ADDITIONAL PAY' && !this.hasAdditionalPay)
            if (row.type === 'ADDITIONAL PAY' && !this.hasAdditionalPay) {
                this.hasAdditionalPay = true;
                return true;
            }
            return false;
        }
    },
    components: {
        AllocationForm,
        AllocationTableRow,
    }
}
</script>
