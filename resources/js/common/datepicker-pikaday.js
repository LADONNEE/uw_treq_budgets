
window.moment = require('moment/min/moment.min');
window.Pikaday = require('pikaday/pikaday.js');

$( document ).ready(function(){
    let datePickerYearRange = function(el) {
        let current = parseInt(moment().format('YYYY'));
        let firstYear = el.data('first-year') || current - 2;
        let lastYear = el.data('last-year') || current + 3;
        if (typeof firstYear === 'string') {
            firstYear = parseInt(firstYear);
        }
        if (typeof lastYear === 'string') {
            lastYear = parseInt(firstYear);
        }
        return [firstYear, lastYear];
    };
    let datePickerGetDate = function(el, field) {
        let dateData = el.data(field);
        if (dateData) {
            let dateObj = moment(dateData);
            if (dateObj.isValid()) {
                return dateObj.toDate();
            }
        }
        return null;
    };
    $('.datepicker').each(function(idx){
        let el = $(this);
        let options = {
            field: this,
            format: el.data('format') || 'M/D/YYYY',
            yearRange: datePickerYearRange(el)
        };
        let minDate = datePickerGetDate(el, 'min-date');
        if (minDate) {
            options.minDate = minDate;
            options.defaultDate = minDate;
        }
        let maxDate = datePickerGetDate(el, 'max-date');
        if (maxDate) {
            options.maxDate = maxDate;
        }
        new Pikaday(options);
    });
});
