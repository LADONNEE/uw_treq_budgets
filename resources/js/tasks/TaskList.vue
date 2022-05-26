<template>
    <div class="task-list">
        <task-item
            v-for="task in store.tasks"
            :key="task.id"
            :task="task"
            :save="saveTask"
            :user-is-creator="store.userIsCreator"
        ></task-item>
        <approval-create v-if="mode ==='approval'" :save="saveTask" @closed="closeForm"></approval-create>
        <task-create v-if="mode ==='task'" :save="saveTask" @closed="closeForm"></task-create>
        <div v-if="!store.reportComplete">
            <button class="btn btn-light" v-if="store.canAddApprover" @click="mode = 'approval'">&plus; Request Approval</button>
            <button class="btn mt-2" v-if="store.userIsAdmin && store.reportStage !== 'UWORG PAY'" @click="showAlert = !showAlert">Admin Override Approvals</button>
            <approval-express
                v-if="showAlert"
                @closeExpress="showAlert = false"
                :save="saveTask"
            ></approval-express>
        </div>

        <json-debug v-if="false" :data="store.tasks"></json-debug>
    </div>
</template>

<script>
    import ApprovalCreate from "./ApprovalCreate";
    import JsonDebug from "../components/JsonDebug";
    import TaskCreate from "./TaskCreate";
    import TaskItem from "./TaskItem";
    import TaskStore from "./task-store";
    import ApprovalExpress from "./ApprovalExpress";
    export default {
        props: ['url'],
        data() {
            return {
                store: new TaskStore(this.url),
                mode: null,
                showAlert: false,
            };
        },
        methods: {
            saveTask(data) {
                this.store.save(data);
            },
            closeForm() {
                this.mode = null;
            },
        },
        components: {
            ApprovalExpress,
            ApprovalCreate,
            JsonDebug,
            TaskCreate,
            TaskItem
        }
    }
</script>
