<template>
    <div :id="'container_' + id">
        <input type="text" v-model="value" :name="inputName" :id="id" class="form-control" />
    </div>
</template>

<script>
import ContactTypeahead from './contact-typeahead';
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
            default: 'contact_typeahead'
        }
    },
    data() {
        return {
            id: this.generatedId(),
            value: this.person,
            contactTypeahead: null
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
            this.contactTypeahead = ContactTypeahead.init('#' + this.id, that.accept);
        });
    },
    beforeDestroy: function () {
        if (this.contactTypeahead) {
            this.contactTypeahead.typeahead('destroy');
            // Typeahead doesn't clean up all of its html
            $('#container_' + this.id).empty();
        }
    }
}
</script>
