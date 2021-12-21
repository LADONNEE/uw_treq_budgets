<template>
    <spotlight-box>
        <form :action="submitUrl" method="post" v-if="store.loaded" class="allocation__form" @submit.prevent="handleSubmit">
            <input type="hidden" name="_token" v-model="token">
            <input-hidden :input="store.inputs.faculty_contact_id"></input-hidden>
            <input-hidden :input="store.inputs._action"></input-hidden>

            <div class="row">
                <input-radio :input="store.inputs.type" class="allocation__type" @input="typeChange"></input-radio>
            </div>

            <div class="row">
                <input-block :input="store.inputs.start_at" class="date-group">
                    <pikaday-input :input="store.inputs.start_at"></pikaday-input>
                </input-block>

                <input-block :input="store.inputs.end_at" class="date-group">
                    <pikaday-input :input="store.inputs.end_at"></pikaday-input>
                </input-block>

                <input-block :input="store.inputs.allocation_category" v-if="!isAdditionalPay">
                    <input-select :input="store.inputs.allocation_category" required @input="categoryChange"></input-select>
                </input-block>

                <input-block :input="store.inputs.additional_pay_category" v-if="isAdditionalPay">
                    <input-select :input="store.inputs.additional_pay_category" required></input-select>
                </input-block>

                <div class="js-budget" v-if="!isCrossUnit">
                    <input-hidden :input="store.inputs.budget_id" id="js-budget-id"></input-hidden>

                    <div class="form-group budget-group">
                        <label class="form-group__label">Budget *</label>
                        <BudgetTypeahead
                            :budget="store.inputs.budget_typeahead.value"
                            :budget-id-input="store.inputs.budget_id"
                            :input="store.inputs.budget_typeahead"
                            data-for="js-budget-id"
                            required
                        >
                        </BudgetTypeahead>
                        <input-error :error="store.inputs.budget_typeahead.error"></input-error>
                    </div>
                </div>

                <input-block :input="store.inputs.cross_unit_budget_no" v-if="isCrossUnit && !isAdditionalPay">
                    <input-text :input="store.inputs.cross_unit_budget_no" placeholder="##-####" required maxlength="7"></input-text>
                </input-block>

                <input-block :input="store.inputs.cross_unit_budget_name" v-if="isCrossUnit && !isAdditionalPay" class="cross-unit-name-group">
                    <input-text :input="store.inputs.cross_unit_budget_name" required maxlength="100"></input-text>
                </input-block>

                <input-block :input="store.inputs.allocation_percent" v-if="!isAdditionalPay" class="percent-group">
                    <div class="input-group">
                        <input-number :input="store.inputs.allocation_percent" required></input-number>
                        <div class="input-group-append">
                            <span class="input-group-text input--append">%</span>
                        </div>
                    </div>
                </input-block>

                <input-block :input="store.inputs.additional_pay_fixed_monthly" v-if="isAdditionalPay" required class="dollar-amount-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text input--prepend">$</span>
                        </div>
                        <input-number :input="store.inputs.additional_pay_fixed_monthly" required></input-number>
                    </div>
                </input-block>

                <input-block :input="store.inputs.pca_code">
                    <input-text :input="store.inputs.pca_code" placeholder="xxx/xxx/xxxxxx"></input-text>
                </input-block>
            </div>

            <div class="js-has-confirm">
                <div class="js-home-view" v-if="!confirmDelete">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button class="btn btn-secondary" type="button" @click="close">Cancel</button>
                    <button class="btn btn-secondary" v-if="task === 'edit'" @click.prevent="confirmDelete = !confirmDelete">Delete</button>
                </div>

                <div v-if="confirmDelete">
                    <div class="my-3 p-3 border rounded border-danger text-danger">
                        Are you sure you want to completely delete this allocation?
                    </div>
                    <button class="btn btn-primary" type="button" @click="deleteAllocation">Confirm Delete</button>
                    <button class="btn btn-secondary" type="button" @click="confirmDelete = !confirmDelete">Cancel</button>
                </div>
            </div>
        </form>
    </spotlight-box>
</template>

<script>
import AllocationsFormStore from "./allocations-form-store";
import BudgetTypeahead from "../../components/BudgetTypeahead";
import InputBlock from "../../forms/InputBlock";
import InputRadio from "../../forms/InputRadio"
import InputError from '../../components/InputError';
import InputNumber from "../../forms/InputNumber";
import InputSelect from "../../forms/InputSelect";
import InputText from "../../forms/InputText";
import InputHidden from "../../forms/InputHidden";
import PikadayInput from "../../forms/PikadayInput";
import SpotlightBox from "../../common/SpotlightBox";

export default {
    props: ['token', 'submitUrl', 'task', 'facultyId', 'allocationId'],
    data () {
        return {
            store: new AllocationsFormStore(this.facultyId, this.allocationId),
            confirmDelete: false,
            type: null,
            category: null,
        };
    },
    computed: {
        isAdditionalPay() {
            if (this.type) {
                return this.type === 'ADDITIONAL PAY';
            }
            return this.store.inputs.type.value === 'ADDITIONAL PAY';
        },
        isCrossUnit() {
            if (this.category) {
                return this.category === 'CROSS UNIT EFFORT';
            }
            return this.store.inputs.allocation_category.value === 'CROSS UNIT EFFORT';
        },
    },
    methods: {
        close() {
            this.$emit('close');
        },
        typeChange(value) {
            this.type = value;
        },
        categoryChange(value) {
            this.category = value;
        },
        handleSubmit() {
            this.store.store(this.allocationId, () => {
                this.$emit('dataUpdated');
                this.close();
            });
        },
        deleteAllocation() {
            this.store.inputs._action.value = 'delete';
            this.handleSubmit();
        },
    },
    components: {
        InputHidden,
        InputRadio,
        BudgetTypeahead,
        InputBlock,
        InputNumber,
        InputSelect,
        InputText,
        InputError,
        PikadayInput,
        SpotlightBox
    }
}
</script>
