<template>
    <div>
        <input-block :input="inputs.message">
            <input-textarea :input="inputs.message" rows="3"></input-textarea>
        </input-block>
        <div>
            <button class="btn btn-primary" @click.prevent="confirmApproved">
                Save Approval Not Needed
            </button>
            <button class="btn btn-light" @click.prevent="$emit('closed')">Cancel</button>
        </div>
    </div>
</template>

<script>
import InputBlock from "../forms/InputBlock";
import InputModel from "../forms/input-model";
import InputTextarea from "../forms/InputTextarea";
import randomString from "../utilities/random-string";
export default {
    data() {
        const idSuffix = randomString();
        return {
            inputs: {
                message: new InputModel({
                    id: 'message_' + idSuffix,
                    name: 'message',
                    label: 'Approval Not Needed Note (optional)',
                    value: ''
                }),
            }
        }
    },
    methods: {
        confirmApproved() {
            this.$emit('confirmed', {
                action: 'revision',
                message: this.inputs.message.value
            });
            this.$emit('closed');
        }
    },
    components: {
        InputBlock,
        InputTextarea
    }
}
</script>
