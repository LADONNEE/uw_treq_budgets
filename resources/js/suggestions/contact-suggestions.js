let Bloodhound = require('typeahead.js/dist/bloodhound.min.js');

let make = function() {
    let engine = new Bloodhound({
        datumTokenizer: function (option) {
            return Bloodhound.tokenizers.nonword(option.firstname + ' ' + option.lastname + ' ' + option.uwnetid);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        identify: function(option) {
            return option.id;
        },
        prefetch: {
            url: '/budgets/api/faculty', cache: false
        },
        remote: {
            url: '/budgets/api/faculty?q={{SEARCHTERM}}',
            wildcard: '{{SEARCHTERM}}'
        }
    });

    return function(q, sync, async) {
        engine.search(q, sync, async);
    };
};

export default { make }
