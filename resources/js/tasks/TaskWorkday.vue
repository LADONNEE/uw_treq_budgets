<template>
    <div>
        <input-block :input="inputs.message" v-if="!revisitOnly">
            <input-textarea :input="inputs.message" rows="3"></input-textarea>
        </input-block>
        <input-block :input="inputs.revisit">
            <pikaday-input
                :input="inputs.revisit"
                label="Revisit Date"
                v-model="inputs.revisit.value"
            ></pikaday-input>
            <template v-slot:help><p class="input-help mt-1 text-muted">Date you will be notified to revisit this report.</p></template>
        </input-block>
        <div>
            <button class="btn btn-primary" @click.prevent="confirmApproved">
                <i class="fas fa-thumbs-up"></i> Save
            </button>
            <button class="btn btn-light" @click.prevent="closeForm">Cancel</button>
        </div>
    </div>
</template>

<script>
import InputBlock from "../forms/InputBlock";
import InputModel from "../forms/input-model";
import InputText from "../forms/InputText";
import InputTextarea from "../forms/InputTextarea";
import PikadayInput from "../forms/PikadayInput";
import randomString from "../utilities/random-string";
import moment from "moment";

export default {
    props: ['revisitOnly'],
    data() {
        const idSuffix = randomString();
        return {
            inputs: {
                message: new InputModel({
                    id: 'message_' + idSuffix,
                    name: 'message',
                    label: 'Note (optional)',
                    value: ''
                }),
                revisit: new InputModel({
                    id: 'revisit_at_' + idSuffix,
                    name: 'revisit_at',
                    label: 'Revisit Date (optional)',
                    value: ''
                }),
            },
        }
    },
    methods: {
        confirmApproved() {
            this.$emit('confirmed', {
                action: this.revisitOnly ? 'edit' : 'approve',
                message: this.inputs.message.value,
                revisit_at: this.inputs.revisit.value ? moment(this.inputs.revisit.value, this.dateFormat).format('YYYY-MM-DD') : '',
            });
            this.closeForm();
        },
        closeForm() {
            this.$emit('closed');
            this.$emit('closeEdit');
        }
    },
    components: {
        InputText,
        InputBlock,
        InputTextarea,
        PikadayInput
    }
}
</script>
