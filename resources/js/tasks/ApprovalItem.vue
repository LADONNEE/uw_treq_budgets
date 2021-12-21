<template>
    <div>
        <div v-if="collapsed" class="approval approval-collapsed pointer" @click="collapsed = false">
            <div class="float-right"><i class="fas fa-caret-down"></i></div>
            <div class="icon">
                <span v-if="responseType === 'yes'" class="text-success"><i class="fas fa-thumbs-up"></i></span>
                <span v-else-if="responseType === 'no'" class="text-danger"><i class="fas fa-undo"></i></span>
                <span v-else class="text-secondary"><i class="fas fa-question-circle"></i></span>
            </div>
            <div class="body">
                <div v-if="hasResponse">
                    <div>
                        {{ responseSummary }} {{ responseAt }}
                    </div>
                </div>
                <div v-else>
                    <div class="response-empty">
                        {{ responseSummary }}
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="approval">
            <div class="float-right" @click="collapsed = true"><i class="fas fa-caret-up pointer"></i></div>
            <div class="icon">
                <span v-if="responseType === 'yes'" class="text-success"><i class="fas fa-thumbs-up"></i></span>
                <span v-else-if="responseType === 'no'" class="text-danger"><i class="fas fa-undo"></i></span>
                <span v-else class="text-secondary"><i class="fas fa-question-circle"></i></span>
            </div>
            <div class="body">
                <p class="description pointer" @click="collapsed = true">{{ responseSummary }}</p>
                <div v-if="!isResponseOnly" class="mb-3">
                    <div class="request">
                        <div class="text-sm-bold">{{ requestedAt }}</div>
                        <div>{{ requestSummary }}</div>
                    </div>
                    <div class="quote mt-2">{{ ask }}</div>
                </div>
                <div v-if="hasResponse" class="mb-3">
                    <div class="response">
                        <div class="text-sm-bold">{{ responseAt }}</div>
                        <div>{{ responseSummary }}{{ behalf }}</div>
                    </div>
                    <div class="quote mt-2">{{ message }}</div>
                </div>
                <div v-else class="mb-3">
                    <div class="response-empty">Awaiting response</div>
                    <div class="mt-3">
                        <a v-if="buttonSendFoward" :href="url" class="btn btn-primary">Send Forward</a>
                        <a v-if="buttonApprove" :href="url" class="btn btn-primary"><i class="fas fa-thumbs-up"></i> Approve</a>
                        <a v-if="buttonApprove" :href="url + '?r=no'" class="btn btn-light"><i class="fas fa-undo"></i> Send back</a>
                        <a v-if="buttonCancel" :href="urlCancel" class="btn btn-link text-danger">&times; Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AppIcon from '../components/Icon';
    export default {
        props: {
            approval: Object,
            canEdit: {
                type: Boolean,
                default: true
            }
        },
        computed: {
            buttonSendFoward() {
                return this.canEdit && this.canApprove && this.isSentBack;
            },
            buttonApprove() {
                return this.canEdit && this.canApprove && ! this.isSentBack;
            },
            buttonCancel() {
                return this.canEdit && this.canCancel;
            }
        },
        data() {
            let data = Object.assign({}, this.approval);
            data.collapsed = data.hasResponse;
            return data;
        },
        mounted() {
            if (this.approval.id === 187)
                console.log(this.approval);
        },
        components: {
            AppIcon
        }
    }
</script>

<style scoped>
    .quote {
        white-space: pre-line;
    }
    .approval.approval-collapsed {
        border: none;
        padding: 0 16px;
        margin-bottom: 32px;
    }
    .approval.approval-collapsed .icon {
        float: left;
        padding-left: 8px;
        width: 52px;
        font-size: 24px;
        color: #1f497d;
    }
    .approval.approval-collapsed .body {
        padding-top: 8px;
    }
</style>