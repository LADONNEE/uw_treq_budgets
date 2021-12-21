import axios from 'axios';

class NoteStore {

    constructor(id, section) {
        this.apiRoot = '/budgets/effort/api/notes/';
        this.id = id;
        this.section = section || '';
        this.notes = [];
        this.loaded = false;
        this.refresh();
    }

    create(noteText) {
        if (!this.section) {
            console.log('NoteStore - cannot create note without section');
            return;
        }
        let apiUrl = this.apiRoot + this.id + '/' + this.section;
        let that = this;
        axios({
            method: 'post',
            url: apiUrl,
            data: {
                note: noteText
            }
        })
            .then(function(response) {
                that.wasCreated(response);
            })
            .catch(function(error) {
                that.apiError(error);
            });
    }

    wasCreated(response) {
        this.notes.unshift(response.data);
    }

    update(data) {
        let apiUrl = this.apiRoot +  data.id;
        let that = this;
        axios({
            method: 'post',
            url: apiUrl,
            data: data
        })
            .then(function(response) {
                that.wasUpdated(response);
            })
            .catch(function(error) {
                that.apiError(error);
            });
    }

    wasUpdated(response) {
        let note = response.data;
        for (let i = 0; i < this.notes.length; ++ i) {
            if (note.id === this.notes[i].id) {
                this.notes.splice(i, 1, note);
                break;
            }
        }
    }

    delete(id) {
        let apiUrl = this.apiRoot + id;
        let that = this;
        axios({
            method: 'post',
            url: apiUrl,
            data: {
                "_action": "delete"
            }
        })
            .then(function(response) {
                that.wasDeleted(response);
            })
            .catch(function(error) {
                that.apiError(error);
            });
    }

    wasDeleted(response) {
        if (response.data.message !== 'deleted' || ! response.data.id) {
            console.log('NoteStore.wasDeleted() unexpected response: ' + response.data);
            return;
        }
        const id = response.data.id;
        for (let i = 0; i < this.notes.length; ++ i) {
            if (id === this.notes[i].id) {
                this.notes.splice(i, 1);
                break;
            }
        }
    }

    refresh() {
        let url = this.apiRoot + this.id;
        if (this.section) {
            url = url + '/' + this.section;
        }
        let that = this;
        axios({
            method: 'get',
            url: url
        })
            .then(function (response) {
                that.applyState(response);
            })
            .catch(function (response) {
                that.apiError(response);
            });
    }

    applyState(response) {
        this.notes = response.data;
        this.loaded = true;
    }

    apiError(error) {
        console.log('NoteStore.apiError()');
        console.log(error.response);
        console.log(error);
    }

}

export default NoteStore;
