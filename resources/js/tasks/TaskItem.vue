<template>
    <collapsing-container
        :expanded="expanded"
        :collapsed-text="getType(task.type)"
        :response-type="task.responseType"
        :response="task.response"
        @expand="() => this.expanded = true"
    >
        <div class="task__name pointer" @click="expanded = false">{{ getType(task.type) }} <span v-if="task.response === 'REVISION ONLY'">- Approval Not Needed</span></div>
        <section class="request">
            <div class="text-sm-bold">Effort Report Submitted {{ task.createdAt }}</div>
            <div v-if="!task.isUaapay">{{ task.taskSummary }}</div>
            <div v-if="task.description" class="quote">{{ task.description }}</div>
        </section>
        <section class="response">
            <div class="text-sm-bold">{{ task.completedAt }}</div>
            <div v-if="task.isApproved && !task.isUaapay">{{ toTitleCase(task.response) }}{{ task.behalf }}</div>
            <div v-if="task.isApproved && task.isUaapay">{{ task.taskSummary }}</div>
            <div v-if="task.message" class="quote">{{ task.message }}</div>
            <div v-if="task.isUaapay && task.revisitDate && !editRevisit" class="alert alert-warning">
                <div class="pb-3">
                    <span>Revisit on: {{ task.revisitDate }}</span>
                    <a href v-on:click.prevent="editRevisit = true" class="pl-3" v-if="task.hasWorkdayRole">Edit</a>
                </div>

                <button class="btn btn-primary" v-if="task.hasWorkdayRole" v-on:click.prevent="clearRevisit">Clear Revisit Flag</button>
            </div>
            <task-workday
                class="mt-3"
                v-if="task.isUaapay && task.hasWorkdayRole && editRevisit"
                revisitOnly="true"
                @confirmed="(data) => saveTask(data)"
                @closeEdit="closeEdit"
            ></task-workday>
            <button class="btn btn-primary mt-3"
                v-if="task.isApproved && task.isUaapay && task.hasWorkdayRole && !editRevisit && !task.revisitDate"
                v-on:click.prevent="editRevisit = true">
                    Add Revisit Date
            </button>
        </section>
        <section>
            <task-actions
                :save="save"
                :id="task.id"
                :is-approval="task.isApproval"
                :is-uaapay="task.isUaapay"
                :can-approve="task.canApprove"
                :can-complete="task.canComplete"
                :can-complete-workday="task.canCompleteWorkday"
                :can-reassign="task.canReassign"
                :can-delete="task.canDelete"
                :user-is-creator="userIsCreator"
            ></task-actions>
        </section>
    </collapsing-container>
</template>

<script>
import CollapsingContainer from "./CollapsingContainer";
import TaskActions from "./TaskActions";
import TaskWorkday from "./TaskWorkday";

export default {
    props: ['task', 'save', 'userIsCreator'],
    data() {
        return {
            expanded: this.task.canApprove || (this.task.message && this.task.responseType !== 'revision'),
            editRevisit: false,
        }
    },
    methods: {
        getType: function(taskType) {
            switch (taskType) {
                case 'BUDGET':
                    return 'Budget Manager';
                    break;
                case 'DEFAULT BUDGET':
                    return 'Default Budget Manager';
                case 'FACULTY':
                    return 'Faculty';
                    break;
                case 'UAA PAY':
                    return 'UAA Pay';
                    break;
                default:
                    return 'Approval';
            }
        },
        toTitleCase: function(str) {
            return str.replace(
                /\w\S*/g,
                function(txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                }
            );
        },
        saveTask(data) {
            data.approval_id = this.task.id;
            this.save(data);
        },
        closeEdit() {
            this.editRevisit = false;
        },
        clearRevisit() {
            this.saveTask({
                action: 'edit',
                revisit_at: '',
            })
        }
    },
    components: {
        CollapsingContainer,
        TaskActions,
        TaskWorkday,
    },
}
</script>
