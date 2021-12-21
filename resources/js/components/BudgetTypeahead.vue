<template>
    <div :id="'container_' + id">
        <input v-if="input" type="text" v-model="input.value" :name="inputName" :id="id" class="form-control" :data-for="dataFor" />
        <input v-else type="text" v-model="value" :name="inputName" :id="id" class="form-control" :data-for="dataFor" />
    </div>
</template>

<script>
import BudgetTypeahead from "./budget-typeahead";

export default {
    props: {
        budget: '',
        inputName: {
            type: String,
            required: false,
            default: 'budget_typeahead'
        },
        dataFor: '',
        budgetIdInput: {},
        input: {}
    },
    data() {
        return {
            id: this.generatedId(),
            value: this.budget,
            budgetTypeahead: null,
        };
    },
    watch: {
        value() {
            this.$emit('input', this.value);
        }
    },
    methods: {
        generatedId() {
            return 'bta_' + Math.random().toString(36).substr(2, 9);
        },
        accept(option) {
            if (this.budgetIdInput) {
                this.budgetIdInput.value = option.id;
            }
            this.value = this.budgetTypeahead.val();
            this.$emit('selected', option);
        }
    },
    mounted() {
        this.$nextTick(() => {
            let that = this;
            this.budgetTypeahead = BudgetTypeahead.init('#' + this.id, that.accept);
        });
    },
    beforeDestroy: function () {
        if (this.budgetTypeahead) {
            this.budgetTypeahead.typeahead('destroy');
            // Typeahead doesn't clean up all of its html
            $('#container_' + this.id).empty();
        }
    }
}
</script>
