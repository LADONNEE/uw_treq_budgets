<template>
    <div>
        <div class="alert alert-danger">
            Do you want to delete this task completely?
        </div>
        <div>
            <button class="btn btn-danger" @click.prevent="confirmApproved">
                <i class="fas fa-times"></i> Confirm Delete
            </button>
            <button class="btn btn-light" @click.prevent="$emit('closed')">Cancel</button>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            confirmApproved() {
                this.$emit('confirmed', {
                    action: 'delete'
                });
                this.$emit('closed');
            }
        }
    }
</script>
