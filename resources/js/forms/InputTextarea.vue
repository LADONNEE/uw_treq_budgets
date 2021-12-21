<template>
    <textarea
       :id="input.id"
       :name="input.name"
       class="form-control"
       :class="inputClass"
       v-model="input.value"
       @change="handleChange"
       @keydown="handleKeydown"
       ref="input"
       :rows="rows"
    ></textarea>
</template>

<script>
    import InputModelMixin from "./InputModelMixin";
    export default {
        mixins: [InputModelMixin],
        props: {
            rows: {
                type: String,
                default: '3'
            }
        }
    }
</script>
