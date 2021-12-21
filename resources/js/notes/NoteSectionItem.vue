<template>
    <div class="section-note__width">
        <note-edit v-if="editing"
                   :note-content="note.note"
                   :delete-option="true"
                   @save="(val) => save(val)"
                   @delete="() => doDelete()"
                   @cancel="editing = false"
        ></note-edit>
        <div v-else class="section-note mb-3">
            <div class="card-body">
                <div class="float-right">
                    <button v-if="note.can_edit" class="btn btn-text" @click.prevent="editing = true">edit</button>
                </div>
                <p class="author">{{ note.contact.firstname }} {{ note.contact.lastname }} <span>{{ age }}</span></p>
                <p>
                    <span class="pre-line">{{ note.note }}</span>
                </p>
                <p v-if="note.edited" style="font-size:11px;">{{ note.edited }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import NoteEdit from './NoteEdit';
    export default {
        props: ['note'],
        data() {
            return {
                editing: false
            };
        },
        computed: {
            age() {
                return moment(this.note.created_at).fromNow();
            }
        },
        watch: {
            editing(val) {
                this.$emit('active', val);
            }
        },
        methods: {
            save(val) {
                this.$emit('update', {
                    id: this.note.id,
                    note: val
                });
                this.editing = false;
            },
            doDelete() {
                this.$emit('delete', this.note.id);
                this.$emit('active', false);
            }
        },
        components: {
            NoteEdit
        }
    }
</script>
