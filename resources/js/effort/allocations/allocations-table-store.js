class AllocationsTableStore {
    constructor(faculty_id) {
        this.url = `/budgets/api/faculty/${faculty_id}/allocations`
        this.data = [];
        this.loaded = false;
        this.refresh();
    }

    refresh() {
        let self = this;
        axios({
            method: 'get',
            url: this.url
        })
            .then(function (response) {
                self.applyState(response);
            })
            .catch(function (response) {
                self.apiError(response);
            });
    }

    applyState(response) {
        this.data = response.data
        this.loaded = true;
    }

    apiError(response) {
        console.log('AllocationTableStore.apiError()');
        console.log(response);
    }
}

export default AllocationsTableStore;
