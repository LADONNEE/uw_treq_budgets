
let Bloodhound = require("typeahead.js/dist/bloodhound.min.js");

let make = function() {
    let engine = new Bloodhound({
        prefetch: {
            url: '/budgets/api/budgets', // cache: false
        },
        identify: function(datum) {
            return datum.id;
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        datumTokenizer: function(datum) {
            let out = datum.name.split(/\s+/);
            let end = datum.budgetno.slice(3);
            out.push(datum.budgetno);
            out.push(datum.budgetno.slice(0,2) + end);
            out.push(end);
            return out;
        }
    });

    return function(q, sync, async) {
        engine.search(q, sync, async);
    };
};

export default { make }
