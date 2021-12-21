<template>
    <input type="hidden" :id="input.id" :name="input.name" :value="input.value" @change="handleChange" />
</template>

<script>
    import InputModelMixin from "./InputModelMixin";
    export default {
        mixins: [InputModelMixin]
    }
</script>
