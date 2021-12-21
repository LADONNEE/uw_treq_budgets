<template>
    <div>
        <div v-if="canEdit">
            <button
                class="btn btn-secondary mb-3"
                type="button"
                @click="showForm = !showForm"
            >
                + New Allocation / Additional Pay
            </button>

            <allocation-form
                v-if="showForm"
                :submit-url="submitUrl"
                task="create"
                :allocation-id="null"
                :faculty-id="facultyId"
                v-on:close="close"
                :token="token"
                @dataUpdated="tableStore.refresh()"
            ></allocation-form>
        </div>

        <allocation-table
            :table-data="tableStore.data"
            :canEdit="canEdit"
            :faculty-id="facultyId"
            :token="token"
            @dataUpdated="tableStore.refresh()"
        ></allocation-table>
    </div>
</template>

<script>

import AllocationForm from "./AllocationForm";
import AllocationTable from "./AllocationTable";
import AllocationsTableStore from "./allocations-table-store";

export default {
    props: ['token', 'submitUrl', 'canEdit', 'facultyId'],
    data () {
        return {
            tableStore: new AllocationsTableStore(this.facultyId),
            showForm: false,
        };
    },
    methods: {
        close() {
            this.showForm = !this.showForm;
        }
    },
    components: {
        AllocationForm,
        AllocationTable,
    }
}
</script>
