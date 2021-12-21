<template>
    <div class="my-4 border rounded p-3">
        <div class="json-debug-output">{{ dataJson }}</div>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        computed: {
            dataJson() {
                return JSON.stringify(this.data, null, 4);
            }
        }
    }
</script>

<style scoped>
    .json-debug-output {
        white-space: pre;
        font-size: 14px;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
    }
</style>
