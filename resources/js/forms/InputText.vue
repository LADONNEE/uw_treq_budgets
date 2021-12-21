<template>
    <input type="text"
           :id="input.id"
           :name="input.name"
           class="form-control"
           :class="inputClass"
           v-model="input.value"
           @change="handleChange"
           @keydown="handleKeydown"
           ref="input"
    />
</template>

<script>
    import InputModelMixin from "./InputModelMixin";
    export default {
        mixins: [InputModelMixin],
    }
</script>
