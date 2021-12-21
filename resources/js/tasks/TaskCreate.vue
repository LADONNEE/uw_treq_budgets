<template>
    <div class="task task-card create">
        <div class="task__icon">
            <i class="fas fa-plus-circle"></i>
        </div>
        <div class="task__body">
            <div class="text-lg mb-2">Add a Task</div>

            <input-block :input="inputs.person_id">
                <person-typeahead @selected="(option) => this.inputs.person_id.value = option.id"></person-typeahead>
            </input-block>
            <input-block :input="inputs.name">
                <input-text :input="inputs.name"></input-text>
            </input-block>
            <input-block :input="inputs.description">
                <input-textarea :input="inputs.description" rows="4"></input-textarea>
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
                    name: new InputModel({
                        id: 'name_' + idSuffix,
                        name: 'name',
                        label: 'Task Title',
                        required: true,
                        value: ''
                    }),
                    description: new InputModel({
                        id: 'description_' + idSuffix,
                        name: 'description',
                        label: 'Description',
                        value: ''
                    }),
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
                if (!this.inputs.name.value) {
                    this.inputs.name.error = 'Task name cannot be empty';
                    valid = false;
                }
                if (!this.inputs.person_id.value) {
                    this.inputs.person_id.error = 'Must select person task will be assigned to';
                    valid = false;
                }
                if (valid) {
                    this.save({
                        action: 'task',
                        name: this.inputs.name.value,
                        description: this.inputs.description.value,
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
            InputText,
            InputTextarea,
            PersonTypeahead
        }
    }
</script>
