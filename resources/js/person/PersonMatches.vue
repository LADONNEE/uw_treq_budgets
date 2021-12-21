<style scoped>
    .heading {
        font-weight: bold;
        font-style: italic;
        color: #555;
    }
    .match,
    .match:link,
    .match:visited {
        display: block;
        margin: 1rem 0;
        background-color: transparent;
        text-decoration: none;
        color: #0000cc !important;
    }
    .match:hover,
    .match:active {
        background-color: #fff720;
    }
    .name {
        display: block;
    }
    .details {
        display: block;
        font-size: .75rem;
        color: #555;
    }
</style>

<template>
    <div>
        <p v-show="searchService.suggestions.length > 0" class="heading">Are you looking for...</p>

        <a v-for="person in searchService.suggestions" class="match pointer" @click="selected(person)">
            <span class="name">{{ person.firstname }} {{ person.lastname }}</span>
            <span class="details">
                <span v-if="person.uwnetid">{{ person.uwnetid }}@uw.edu</span>
                {{ person.employeeid }}
            </span>
        </a>
    </div>
</template>

<script>
    export default {
        props: ['searchService'],
        methods: {
            selected(person) {
                this.$emit('selected', person);
            }
        }
    }
</script>
