<template>
    <div style="width: 25%;">
        <div v-if="adding" class="col-md-auto">
            <note-edit note-content=""
                       @save="(val) => save(val)"
                       @cancel="() => setAdding(false)"
            ></note-edit>
        </div>

        <note-item v-for="note in store.notes"
                   :note="note"
                   :key="note.id"
                   @update="(val) => update(val)"
                   @delete="(id) => doDelete(id)"
                   @active="(val) => setEditing(val)"
        ></note-item>

        <div v-if="!active">
            <button class="btn btn-link section-note__button" @click.prevent="setAdding(true)">
                <i class="fas fa-comment"></i> Add a Note
            </button>
        </div>

        <portal-target name="modals"></portal-target>
    </div>
</template>

<script>
    import NoteEdit from './NoteEdit';
    import NoteItem from './NoteSectionItem';
    import NoteStore from './note-store';
    export default {
        props: ['id', 'section'],
        data() {
            return {
                store: new NoteStore(this.id, this.section),
                adding: false,
                active: false
            };
        },
        methods: {
            save(val) {
                this.store.create(val);
                this.setAdding(false);
            },
            update(data) {
                this.store.update(data);
                this.setAdding(false);
            },
            doDelete(id) {
                this.store.delete(id);
                this.setEditing(false);
            },
            setAdding(val) {
                this.adding = val;
                this.active = val;
                this.$emit('active', val);
            },
            setEditing(val) {
                this.adding = false;
                this.active = val;
                this.$emit('active', val);
            }
        },
        components: {
            NoteEdit,
            NoteItem
        }
    }
</script>
