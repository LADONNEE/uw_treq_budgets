<template>
    <div>
        <div class="text-lg mb-2">Reassign Task</div>

        <input-block :input="inputs.person_id">
            <person-typeahead @selected="(option) => this.inputs.person_id.value = option.id"></person-typeahead>
        </input-block>

        <div>
            <button class="btn btn-primary" @click.prevent="validateAndSave">Reassign</button>
            <button class="btn btn-secondary" @click.prevent="cancel">Cancel</button>
        </div>
    </div>
</template>

<script>
    import InputBlock from "../forms/InputBlock";
    import InputModel from "../forms/input-model";
    import PersonTypeahead from "../components/PersonTypeahead";
    import randomString from "../utilities/random-string";
    export default {
        props: [ 'save' ],
        data() {
            const idSuffix = randomString();
            return {
                inputs: {
                    person_id: new InputModel({
                        id: 'person_id_' + idSuffix,
                        name: 'person_id',
                        label: 'Assign To',
                        required: true,
                        value: ''
                    })
                },
                person_id: null
            }
        },
        methods: {
            validateAndSave() {
                let valid = true;
                if (!this.inputs.person_id.value) {
                    this.inputs.person_id.error = 'Must select person task will be assigned to';
                    valid = false;
                }
                if (valid) {
                    this.$emit('confirmed', {
                        action: 'reassign',
                        person_id: this.inputs.person_id.value,
                    });
                    this.$emit('closed');
                }
            },
            cancel() {
                this.$emit('closed');
            }
        },
        components: {
            InputBlock,
            PersonTypeahead
        }
    }
</script>
