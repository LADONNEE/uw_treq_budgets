<template>
    <div v-if="expanded" class="task task-card">
        <div class="task__icon">
            <i class="fas" :class="icon"></i>
        </div>
        <div class="task__body">
            <slot></slot>
        </div>
        <div class="task__collapse">
            <i class="fas fa-caret-up"></i>
        </div>
    </div>
    <div v-else class="task task-collapsed">
        <div class="task__icon">
            <i class="fas" :class="icon"></i>
        </div>
        <div class="task__body">
            <div class="task__name pointer" @click.prevent="expand">{{ collapsedText }} <span v-if="response === 'REVISION ONLY'">- Approval Not Needed</span></div>
        </div>
        <div class="task__collapse">
            <i class="fas fa-caret-down pointer" @click.prevent="expand"></i>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['responseType', 'expanded', 'collapsedText', 'response'],
        computed: {
            icon() {
                const approved = this.responseType === 'yes';
                const sentback = this.responseType === 'no';
                const completed = this.responseType === 'complete';
                const revision = this.responseType === 'revision';
                return {
                    'fa-thumbs-up': approved,
                    'fa-undo': sentback,
                    'fa-check': completed,
                    'fa-question-circle': !approved && !sentback && !completed && !revision,
                    'fa-minus-circle': revision,
                    'text-danger': sentback,
                    'text-success': approved || completed,
                };
            }
        },
        methods: {
            expand() {
                this.$emit('expand');
            },
        }
    }
</script>
