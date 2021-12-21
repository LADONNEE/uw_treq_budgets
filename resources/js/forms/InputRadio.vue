<template>
    <div>
        <div class="form-check" v-for="option in input.options">
            <label v-if="option.label" :for="input.id" class="form-check__label">
                <input type="radio"
                       :id="input.id"
                       :name="input.name"
                       :class="inputClass"
                       v-model="input.value"
                       @change="handleChange"
                       @keydown="handleKeydown"
                       ref="input"
                       :value="option.value"
                />
                {{ option.label }}
                <span v-if="input.required" title="Required" class="required">*</span>
            </label>
        </div>
    </div>
</template>

<script>
import InputModelMixin from "./InputModelMixin";
export default {
    mixins: [InputModelMixin]
}
</script>
