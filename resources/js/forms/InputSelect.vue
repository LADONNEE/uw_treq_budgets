<template>
    <select :id="input.id"
            :name="input.name"
            class="form-control"
            v-model="input.value"
            @change="handleChange"
            @keydown="handleKeydown"
            ref="input"
    >
        <option v-for="option in input.options"
                :value="option.value"
                :key="option.value"
        >{{ option.label }}</option>
    </select>
</template>

<script>
    import InputModelMixin from "./InputModelMixin";
    export default {
        mixins: [InputModelMixin]
    }
</script>
