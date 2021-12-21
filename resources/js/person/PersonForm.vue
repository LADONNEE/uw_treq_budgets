<template>
    <div>
        <form :action="submitUrl" method="post" @submit="validate($event)">
            <input type="hidden" name="_token" v-model="token">
            <input type="hidden" name="person_id" v-model="person_id">
            <input type="hidden" name="email" v-model="email">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="uwnetid">UW NetID <em class="required">*</em></label>
                        <input type="text" class="form-control" id="uwnetid"  v-model="uwnetid" @keyup="search" :disabled="isLocked" />
                        <input type="hidden" name="uwnetid" :value="uwnetid">
                        <input-error :error="errors.uwnetid"></input-error>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employeeid">UW Employee ID</label>
                        <input type="text" class="form-control" id="employeeid" v-model="employeeid" @keyup="search" :disabled="isLocked" />
                        <input type="hidden" name="employeeid" :value="employeeid">
                        <input-error :error="errors.employeeid"></input-error>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="firstname">First name <em class="required">*</em></label>
                        <input type="text" class="form-control" id="firstname" v-model="firstname" @keyup="search" :disabled="isLocked" />
                        <input type="hidden" name="firstname" :value="firstname">
                        <input-error :error="errors.firstname"></input-error>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lastname">Last name <em class="required">*</em></label>
                        <input type="text" class="form-control" id="lastname" v-model="lastname" @keyup="search" :disabled="isLocked" />
                        <input type="hidden" name="lastname" :value="lastname">
                        <input-error :error="errors.lastname"></input-error>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 js-budget">
                    <input type="hidden" name="default_budget_id" v-model="default_budget_id" id="js-budget-id">

                    <div class="form-group">
                        <label>Default Budget</label>
                        <BudgetTypeahead
                            :budget="budget_search"
                            :value="budget_search"
                            data-for="js-budget-id"
                            @input="(newBudget) => {budget_search = newBudget;}"
                            @selected="(option) => budgetSelected(option)"
                            @clear="clearBudget">
                        </BudgetTypeahead>
                        <input-error :error="errors.budget_search"></input-error>
                    </div>
                </div>
                <div class="col-md-6 js-contact">
                    <input type="hidden" name="fiscal_person_id" v-model="fiscal_person_id">

                    <div class="form-group">
                        <label>Finance Manager</label>
                        <ContactTypeahead
                            :person="contact_search"
                            @selected="(option) => financeManagerSelected(option)">
                        </ContactTypeahead>
                        <input-error :error="errors.contact_search"></input-error>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="end-at">End Date</label>
                        <input type="text" class="form-control" id="end-at" v-model="end_at" />
                        <input type="hidden" name="end_at" :value="end_at">
                        <input-error :error="errors.end_at"></input-error>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="checkbox" id="80-20" v-model="is_80_20" />
                        <label for="80-20">Faculty is 80/20</label>
                        <input type="hidden" name="is_80_20" :value="is_80_20">
                        <input-error :error="errors.is_80_20"></input-error>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" @click.prevent="clear()">Clear</button>
                    </div>
                </div>
            </div>
        </form>
        <p v-if="isLocked" class="text-muted font-italic">
            Person fields from official UW person information can not be changed
        </p>
    </div>
</template>

<script>
    import BudgetTypeahead from "../components/BudgetTypeahead";
    import ContactTypeahead from '../components/ContactTypeahead';
    import InputError from '../components/InputError';

    export default {
        props: ['searchService', 'selectedPerson', 'submitUrl', 'token'],
        data: function () {
            let data = {
                person_id: '',
                email: '',
                worker_id: '',
                fiscal_person_id: '',
                default_budget_id: '',
                uwnetid: '',
                employeeid: '',
                firstname: '',
                lastname: '',
                budget_search: '',
                contact_search: '',
                is_80_20: 0,
                end_at: '',
                errors: {
                    firstname: '',
                    lastname: '',
                    uwnetid: '',
                    budget_search: '',
                }
            };

            const state = window.facultyFormState || [];

            for (const property in state) {
                data[property] = state[property];
            }

            return data;
        },
        computed: {
            isLocked() {
                return !! this.person_id;
            }
        },
        watch: {
            selectedPerson() {
                if (this.selectedPerson) {
                    this.person_id = this.selectedPerson.person_id;
                    this.email = this.selectedPerson.email;
                    this.uwnetid = this.selectedPerson.uwnetid;
                    this.employeeid = this.selectedPerson.employeeid;
                    this.firstname = this.selectedPerson.firstname;
                    this.lastname = this.selectedPerson.lastname;
                    this.default_budget_id = this.selectedPerson.default_budget_id;
                    this.budget_search = this.selectedPerson.budgetno;
                    this.fiscal_person_id = this.selectedPerson.fiscal_person_id;
                    this.contact_search = this.selectedPerson.fiscal_name;
                    this.is_80_20 = this.selectedPerson.is_80_20;
                    this.end_at = this.selectedPerson.end_at;
                } else {
                    this.clearLocal();
                }
            }
        },
        methods: {
            clear() {
                if (this.selectedPerson) {
                    this.$emit('cleared');
                } else {
                    this.clearLocal();
                }
            },
            clearLocal() {
                this.person_id = '';
                this.email = '';
                this.uwnetid = '';
                this.employeeid = '';
                this.firstname = '';
                this.lastname = '';
                this.budget_search = '';
                this.contact_search = '';
                this.is_80_20 = 0;
                this.end_at = '';
            },
            clearBudget() {
                this.default_budget_id = '';
            },
            isUwnetidValid(uwnetid) {
                const u = uwnetid.toLowerCase().trim();
                const regex = /^[a-z][a-z0-9]{0,7}$/;
                return regex.test(u);
            },
            search() {
                let query = {};
                if (this.uwnetid) {
                    query.uwnetid = this.uwnetid;
                }
                if (this.employeeid) {
                    query.employeeid = this.employeeid;
                }
                if (this.firstname) {
                    query.firstname = this.firstname;
                }
                if (this.lastname) {
                    query.lastname = this.lastname;
                }
                this.searchService.query(query);
            },
            validate(e) {
                if (!this.firstname) {
                    this.errors.firstname = 'First name is required';
                    e.preventDefault();
                }
                if (!this.lastname) {
                    this.errors.lastname = 'Last name is required';
                    e.preventDefault();
                }
                if (!this.uwnetid) {
                    this.errors.uwnetid = 'UW NetID is required';
                    e.preventDefault();
                }
                if (!this.isUwnetidValid(this.uwnetid)) {
                    this.errors.uwnetid = 'UW NetID is not valid';
                    e.preventDefault();
                }
                if (this.budget_search && !this.default_budget_id) {
                    this.errors.budget_search = 'Please select from list';
                    e.preventDefault();
                }
            },
            financeManagerSelected(option) {
                this.fiscal_person_id = option.id;
            },
            budgetSelected(option) {
                this.default_budget_id = option.id;
                this.budget_search = option.budgetno + ' (' + option.name + ')';
            },
        },
        components: {
            BudgetTypeahead,
            ContactTypeahead,
            'input-error': InputError,
        }
    }
</script>
