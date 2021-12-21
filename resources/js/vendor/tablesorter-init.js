require('tablesorter');

function tablesorter_init() {
    $('table.sortable').tablesorter({
        "widgets": ['zebra'],
        "widgetOptions": {
            zebra: ["evenrow", "oddrow"]
        }
    });
}

$( document ).ready(function(){
    tablesorter_init();
});
