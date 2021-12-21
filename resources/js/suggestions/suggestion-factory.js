let suggestionsInstances = {};

import makePerson from './person-suggestions';
import makeContact from './contact-suggestions.js';
import makeBudget from './budget-suggestions.js';

let suggestionsFactories = {
    person: makePerson,
    contact: makeContact,
    budget: makeBudget,
};

let suggestionsCreate = function (name) {
    if (suggestionsFactories.hasOwnProperty(name)) {
        return suggestionsFactories[name].make();
    }
    throw "No suggestion configured for " + name;
};

let suggestions = function (name) {
    if (!suggestionsInstances.hasOwnProperty(name)) {
        suggestionsInstances[name] = suggestionsCreate(name);
    }
    return suggestionsInstances[name];
};

export {suggestions}
