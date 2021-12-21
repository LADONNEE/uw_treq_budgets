<template>
    <div>
        Hello World
    </div>
</template>

<script>
    import SearchService from '../common/search-service';
    export default {
        props: ['search', 'typeahead', 'value'],
        data: function() {
            return {
                service: new SearchService(this.suggestUrl),
                query: this.value,
                selected: null,
                index: null,
            };
        },
        methods: {
            suggest: function() {
                this.service.query(this.query);
            }
        },
        computed: {
            suggestions: function() {
                return this.service.suggestions;
            },
            hasSuggestions: function() {
                return this.service.suggestions.length > 0;
            }
        },
    }
</script>
