<template>
    <div class="task task-card create">
        <div class="task__icon">
            <i class="fas fa-plus-circle"></i>
        </div>
        <div class="task__body">
            <div class="text-lg mb-2">Request an Approval</div>

            <input-block :input="inputs.person_id">
                <person-typeahead @selected="(option) => this.inputs.person_id.value = option.person_id"></person-typeahead>
            </input-block>
            <input-block :input="inputs.description">
                <input-textarea :input="inputs.description" rows="3"></input-textarea>
            </input-block>

            <div>
                <button class="btn btn-primary" @click.prevent="validateAndSave">Save</button>
                <button class="btn btn-secondary" @click.prevent="cancel">Cancel</button>
            </div>
        </div>
        <div class="task__collapse"></div>
    </div>
</template>

<script>
    import InputBlock from "../forms/InputBlock";
    import InputModel from "../forms/input-model";
    import InputText from "../forms/InputText";
    import InputTextarea from "../forms/InputTextarea";
    import PersonTypeahead from "../components/PersonTypeahead";
    import randomString from "../utilities/random-string";
    export default {
        props: [ 'save' ],
        data() {
            const idSuffix = randomString();
            return {
                inputs: {
                    description: new InputModel({
                        id: 'description_' + idSuffix,
                        name: 'description',
                        label: 'Description',
                        value: ''
                    }),
                    person_id: new InputModel({
                        id: 'person_id_' + idSuffix,
                        name: 'person_id',
                        label: 'Approval From',
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
                    this.save({
                        action: 'approval',
                        description: this.inputs.description.value,
                        person_id: this.inputs.person_id.value,
                    });
                    this.$emit('closed');
                    this.$forceUpdate();
                }
            },
            cancel() {
                this.$emit('closed');
            }
        },
        components: {
            InputBlock,
            InputText,
            InputTextarea,
            PersonTypeahead
        }
    }
</script>
