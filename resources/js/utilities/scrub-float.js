let scrubFloat = function(val) {
    if (val === '' || val === null) {
        return null;
    }
    if (typeof val === 'number') {
        val = val.toString();
    }
    let num = val.replace(/[^0-9\.]/g, '');
    return Math.round(num * 10000) / 10000.0;
};

export default scrubFloat;
