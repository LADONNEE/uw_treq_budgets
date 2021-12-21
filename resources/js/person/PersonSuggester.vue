<template>
    <div>
        <div class="row">
            <div class="col-md-9">
                <person-form :search-service="service"
                             :selectedPerson="selectedPerson"
                             :submit-url="submitUrl"
                             :token="token"
                             @cleared="() => clear()"
                ></person-form>
            </div>
            <div class="col-md-3">
                <person-matches :search-service="service"
                                @selected="(person) => confirmPerson(person)"
                ></person-matches>
            </div>
        </div>
    </div>
</template>

<script>
    import SearchService from '../common/search-service';
    import PersonForm from './PersonForm';
    import PersonMatches from './PersonMatches';
    export default {
        props: ['suggestUrl', 'submitUrl', 'token'],
        data: function() {
            return {
                selectedPerson: null,
                service: new SearchService(this.suggestUrl)
            };
        },
        methods: {
            clear() {
                this.selectedPerson = null;
            },
            confirmPerson(person) {
                this.selectedPerson = person;
            }
        },
        components: { PersonForm, PersonMatches }
    }
</script>
