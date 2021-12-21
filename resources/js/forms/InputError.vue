<template>
    <div class="inputerror" v-if="input.hasError">{{ input.error }}</div>
</template>

<script>
    export default {
        props: ['input']
    }
</script>
