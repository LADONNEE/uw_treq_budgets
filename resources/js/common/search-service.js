
import axios from 'axios';
import _ from 'lodash';

class SearchService {

    constructor(url) {
        this.url = url;
        this.input = '';
        this.suggestions = [];
        this.debounce = _.debounce(this.update.bind(this), 250);
    }

    query(input) {
        if (typeof input === 'object') {
            input = JSON.stringify(input);
        }
        this.input = input;
        this.debounce();
    }

    update() {
        let url = this.url + '?q=' + encodeURIComponent(this.input);
        axios.get(url)
            .then(function(response) {
                this.suggestions = response.data;
            }.bind(this))
            .catch(function(error) {
                console.log(error);
            });
    }
}

export default SearchService;
