<template>
    <div v-show="error" class="invalid-feedback" style="display:inline;">{{ error }}</div>
</template>

<script>
    export default {
        props: ['error']
    }
</script>