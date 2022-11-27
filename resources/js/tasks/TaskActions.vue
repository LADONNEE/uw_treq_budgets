<template>
    <div v-if="active">
        <component
            v-if="actionComponent"
            v-bind:is="actionComponent"
            @closed="closeForm"
            @confirmed="(data) => saveTask(data)"
        ></component>
        <div v-else>
            <button v-if="canComplete && !isUworgpay" class="btn btn-primary ml-2" @click.prevent="mode = 'approve'" :disabled="!canApprove">
                <i class="fas fa-thumbs-up"></i> Approve
            </button>
            <button v-if="canCompleteWorkday && isUworgpay" class="btn btn-primary ml-2" @click.prevent="mode = 'workday'" :disabled="!canApprove">
                <i class="fas fa-badge-check"></i> Entered in Workday
            </button>
            <button v-if="canComplete || (canCompleteWorkday && isUworgpay)" class="btn btn-light ml-2" @click.prevent="mode = 'send-back'" :disabled="!canApprove">
                <i class="fas fa-undo"></i> Send Back
            </button>
            <i v-tooltip="" class="fal fa-trash task__trash-icon pt-2" v-if="userIsCreator" @click.prevent="mode = 'revision'"></i>
            <p v-if="!canApprove" class="text-sm text-danger">These buttons will become active once it's your turn to review.</p>
        </div>
    </div>
</template>

<script>
import ApprovalApprove from "./ApprovalApprove";
import ApprovalSendBack from "./ApprovalSendBack";
import ApprovalRevision from "./ApprovalRevision";
import TaskComplete from "./TaskComplete";
import TaskDelete from "./TaskDelete";
import TaskReassign from "./TaskReassign";
import TaskWorkday from "./TaskWorkday";

export default {
    directives: {
        tooltip: {
            inserted: function (el, binding) {
                $(el).tooltip({
                    title: 'Approval Not Needed',
                    placement: 'bottom'
                });
            }
        }
    },
    props: ['id', 'canApprove', 'canComplete', 'canCompleteWorkday', 'canDelete', 'canReassign', 'save', 'isApproval', 'isUworgpay', 'userIsCreator'],
    data() {
        return {
            mode: null
        };
    },
    computed: {
        active() {
            return this.canComplete || (this.canCompleteWorkday && this.isUworgpay) || this.canDelete;
        },
        actionComponent() {
            switch (this.mode) {
                case 'approve':
                    return 'ApprovalApprove';
                case 'revision':
                    $('.tooltip').hide();
                    return 'ApprovalRevision';
                case 'send-back':
                    return 'ApprovalSendBack';
                case 'workday':
                    return 'TaskWorkday';
                default:
                    return null;
            }
        }
    },
    methods: {
        closeForm() {
            this.mode = null;
        },
        saveTask(data) {
            data.approval_id = this.id;
            this.save(data);
        }
    },
    components: {
        ApprovalApprove,
        ApprovalRevision,
        ApprovalSendBack,
        TaskComplete,
        TaskDelete,
        TaskReassign,
        TaskWorkday
    }
}
</script>
