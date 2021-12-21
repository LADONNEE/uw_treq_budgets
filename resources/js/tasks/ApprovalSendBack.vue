<template>
    <div>
        <input-block :input="inputs.message">
            <input-textarea :input="inputs.message" rows="4"></input-textarea>
        </input-block>
        <div>
            <button class="btn btn-danger" @click.prevent="confirmApproved">
                <i class="fas fa-undo"></i> Save Sent-Back
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
                        label: 'Sent-Back Reason (required)*',
                        value: ''
                    }),
                }
            }
        },
        methods: {
            confirmApproved() {
                let valid = true;
                if (!this.inputs.message.value) {
                    this.inputs.message.error = 'Sent-back reason is required';
                    valid = false;
                }
                if (valid) {
                    this.$emit('confirmed', {
                        action: 'send-back',
                        message: this.inputs.message.value
                    });
                    this.$emit('closed');
                }
            }
        },
        components: {
            InputBlock,
            InputTextarea
        }
    }
</script>
