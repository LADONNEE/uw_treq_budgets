<template>
    <div class="mb-3" style="width:20rem;">
        <textarea rows="5" class="form-control" v-model="note" ref="note"></textarea>
        <div class="mt-2">
            <button class="btn btn-primary" @click.prevent="save">Save</button>
            <button class="btn btn-light" @click.prevent="cancel">Cancel</button>
            <button v-if="deleteOption" class="btn btn-link text-danger" @click.prevent="confirmDelete">Delete...</button>

            <portal to="modals">
                <modest-modal :open="deleting" @close="deleting = false">
                    <div class="my-3">Delete Note</div>
                    <div class="note-delete-preview">{{ note }}</div>
                    <div>
                        <button class="btn btn-danger" @click.prevent="doDelete">Confirm Delete</button>
                        <button class="btn btn-light" @click.prevent="deleting = false">Cancel</button>
                    </div>
                </modest-modal>
            </portal>
        </div>
    </div>
</template>

<script>
    import ModestModal from '../components/ModestModal';
    export default {
        props: ['noteContent', 'deleteOption'],
        data() {
            return {
                note: this.noteContent,
                deleting: false
            };
        },
        mounted() {
            this.$nextTick(function() {
                this.$refs.note.focus();
            });
        },
        methods: {
            cancel() {
                this.$emit('cancel');
            },
            save() {
                this.$emit('save', this.note);
            },
            confirmDelete() {
                this.deleting = true;
            },
            doDelete() {
                this.deleting = false;
                this.$emit('delete');
            }
        },
        components: {
            ModestModal
        }
    }
</script>

<style scoped>
    .note-delete-preview {
        margin-bottom: 16px;
        padding: 8px;
        border: 1px solid #ccc;
        border-left: 0 none;
        border-right: 0 none;
        font-size: 12px;
        white-space: pre-line;
    }
</style>
