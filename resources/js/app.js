require('./utilities/polyfills');

window._ = require('lodash');

window.$ = window.jQuery = require('jquery');
require('bootstrap');

// Provide access to Laravel CSRF token
window.csrf_token = function () {
    let header = document.head.querySelector('meta[name="csrf-token"]');
    if (header) {
        return header.content;
    }
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    return null;
};

require("typeahead.js/dist/typeahead.jquery.min.js");
window.Bloodhound = require("typeahead.js/dist/bloodhound.min.js");
require('./budget/budget-person');
require('./budget/manager-select');
require('./components/app-menu');
require('./components/budget-settings');
require('./components/budget-typeahead');
require('./components/contact-typeahead');
require('./components/person-typeahead');
require('./common/datepicker-pikaday');
require('./common/edit-row');
require('./common/has-confirm');
require('./common/link-row');
require('./common/note-add');
require('./components/search-bar');
require('./vendor/tablesorter-init');

// Boot Vue app
import Vue from 'vue';
window.Vue = Vue;
import AllocationList from "./effort/allocations/AllocationList";
import NotesSection from "./notes/NotesSection";
import PersonSuggester from "./person/PersonSuggester";
import PikadayInput from "./forms/PikadayInput";
import PortalVue from 'portal-vue';
import TaskItem from "./tasks/TaskItem";
import TaskList from "./tasks/TaskList";

window.Vue.use(PortalVue);

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf_token();

const app = new Vue({
    el: '#vue_app',
    components: {
        AllocationList,
        PersonSuggester,
        NotesSection,
        PikadayInput,
        TaskItem,
        TaskList,
    },
});
