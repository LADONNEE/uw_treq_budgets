let dollarFormat = function(val, decimalPlaces) {
    if (!val) {
        return '';
    }
    if (typeof decimalPlaces === 'undefined') {
        decimalPlaces = 2;
    }
    if (Intl.NumberFormat) {
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: decimalPlaces
        });
        return formatter.format(val);
    }

    return '$' + (val * 1.0).toFixed(decimalPlaces);
};

export default dollarFormat;
