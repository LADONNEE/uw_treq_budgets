<template>
    <div :id="'container_' + id">
        <input type="text" v-model="value" :name="inputName" :id="id" class="form-control" />
    </div>
</template>

<script>
    import PersonTypeahead from './person-typeahead';
    export default {
        props: {
            person: {
                type: String,
                required: false,
                default: ''
            },
            inputName: {
                type: String,
                required: false,
                default: 'person-typeahead'
            }
        },
        data() {
            return {
                id: this.generatedId(),
                value: this.person,
                personTypeahead: null
            };
        },
        watch: {
            person(val) {
                this.value = val;
            }
        },
        methods: {
            generatedId() {
                return 'pta_' + Math.random().toString(36).substr(2, 9);
            },
            accept(option) {
                this.$emit('selected', option);
            }
        },
        mounted() {
            this.$nextTick(() => {
                let that = this;
                this.personTypeahead = PersonTypeahead.init('#' + this.id, that.accept);
            });
        },
        beforeDestroy: function () {
            if (this.personTypeahead) {
                this.personTypeahead.typeahead('destroy');
                // Typeahead doesn't clean up all of its html
                $('#container_' + this.id).empty();
            }
        }
    }
</script>
