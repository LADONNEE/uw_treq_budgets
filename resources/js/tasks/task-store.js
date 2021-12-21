import axios from 'axios';

class TaskStore {

    constructor(url) {
        this.url = url;
        this.tasks = [];
        this.canAddApprover = false;
        this.userIsAdmin = false;
        this.userIsCreator = false;
        this.loaded = false;
        this.reportStage = '';
        this.reportComplete = false;
        this.refresh();
    }

    save(data) {
        let that = this;
        axios({
            method: 'post',
            url: this.url,
            data: data
        })
            .then(function (response) {
                that.refresh();
                window.location.reload();
            })
            .catch(function (error) {
                that.apiError(error);
            });
    }

    wasSaved(response) {
        this.tasks.unshift(response.data);
    }

    refresh() {
        let that = this;
        axios({
            method: 'get',
            url: this.url
        })
            .then(function (response) {
                that.applyState(response);
            })
            .catch(function (response) {
                that.apiError(response);
            });
    }

    applyState(response) {
        this.tasks = response.data;
        this.loaded = true;
        this.canAddApprover = !response.data.some(task => {
            return task.response === 'SENT BACK';
        });
        this.userIsAdmin = !response.data.some(task => {
            return task.userIsAdmin === false;
        });
        this.userIsCreator = !response.data.some(task => {
            return task.userIsCreator === false;
        });
        this.reportStage = response.data[0].currentStage;
        this.reportComplete = response.data[0].isReportComplete;
        let refreshBtn = document.getElementById('js-order-refresh');
        if (refreshBtn) {
            refreshBtn.click();
        }
    }

    apiError(error) {
        console.log('TaskStore.apiError()');
        console.log(error.response);
        console.log(error);
    }

}

export default TaskStore;
