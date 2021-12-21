<template>
    <div class="form-group">
        <label v-if="input.label" :for="input.id" class="form-group__label">
            {{ input.label }}
            <span v-if="input.required" title="Required" class="required">*</span>
        </label>
        <slot></slot>
        <div class="form-group__error" v-if="input.hasError">{{ input.error }}</div>
        <slot name="help"></slot>
    </div>
</template>

<script>
    export default {
        props: ['input'],
        computed: {
            hasError() {
                return {
                    "has-error": this.input.hasError
                }
            }
        }
    }
</script>
