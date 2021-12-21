class InputModel {
    constructor(state) {
        this.name = state.name || '';
        this.id = state.id || '';
        this.type = state.type || 'text';
        this.valueType = state.valueType || 'text';
        this.label = state.label || '';
        this.required = state.required || false;
        this.value = state.value || '';
        this.hasError = state.hasError || false;
        this._error = state.error || '';
        this.options = state.options || [];
    }

    get error() {
        return this._error;
    }

    set error(val) {
        this._error = val || '';
        this.hasError = !! this._error;
    }
}

export default InputModel;
