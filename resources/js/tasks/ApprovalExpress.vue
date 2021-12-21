<template>
    <div class="alert alert-warning mt-2" role="alert">
        <p>Are you sure you want to approve on behalf of the approvers above and move this report into the Workday stage?</p>
        <input-block :input="inputs.message">
            <input-textarea :input="inputs.message" rows="3"></input-textarea>
        </input-block>
        <div>
            <button class="btn btn-primary" @click.prevent="confirmApproved">
                <i class="fas fa-thumbs-up"></i> Save Approved
            </button>
            <button class="btn btn-light" @click.prevent="$emit('closeExpress')">Cancel</button>
        </div>
    </div>
</template>

<script>
import InputBlock from "../forms/InputBlock";
import InputModel from "../forms/input-model";
import InputTextarea from "../forms/InputTextarea";
import randomString from "../utilities/random-string";
export default {
    props: ['save'],
    data() {
        const idSuffix = randomString();
        return {
            inputs: {
                message: new InputModel({
                    id: 'message_' + idSuffix,
                    name: 'message',
                    label: 'Admin Override Reason (required)*',
                    value: ''
                }),
            }
        }
    },
    methods: {
        confirmApproved() {
            let valid = true;
            if (!this.inputs.message.value) {
                this.inputs.message.error = 'Admin Override reason is required';
                valid = false;
            }

            if (valid) {
                this.save({
                    action: 'express-approve',
                    message: 'Admin approval reason: ' + this.inputs.message.value,
                });
                this.$emit('closed');
            }
        },
    },
    components: {
        InputBlock,
        InputTextarea
    }
}
</script>
