class AllocationsFormStore {
    constructor(faculty_id, allocation_id) {
        this.url = allocation_id !== null ? `/budgets/effort/allocations/${allocation_id}/faculty/${faculty_id}/edit` : `/budgets/effort/allocations/faculty/${faculty_id}/create`;
        this.inputs = {};
        this.wasValidated = false;
        this.hasErrors = false;
        this.loaded = false;
        this.facultyId = faculty_id;
        this.refresh();
    }

    store(allocationId, callback) {
        this.loaded = false;
        let apiUrl;

        if (allocationId) {
            apiUrl = `/budgets/effort/allocations/${allocationId}/faculty/${this.facultyId}`;
        } else {
            apiUrl = `/budgets/effort/allocations/faculty/${this.facultyId}`;
        }

        let formData = Object.keys(this.inputs).reduce((acc, cur) => {
            acc[cur] = this.inputs[cur].value;
            return acc;
        }, {});

        let self = this;
        axios({
            method: 'post',
            url: apiUrl,
            data: formData
        })
            .then(function (response) {
                if (!response.data.hasErrors) {
                    callback();
                } else {
                    self.applyState(response)
                }
            })
            .catch(function (response) {
                self.apiError(response);
            });
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
        const inputs = response.data.inputs;

        for (let i = 0; i < inputs.length; ++ i) {
            let input = inputs[i];
            this.inputs[input.name] = input;
        }
        this.wasValidated = response.wasValidated;
        this.hasErrors = response.hasErrors;
        this.loaded = true;
    }

    apiError(response) {
        console.log('AllocationFormStore.apiError()');
        console.log(response);
    }
}

export default AllocationsFormStore;
