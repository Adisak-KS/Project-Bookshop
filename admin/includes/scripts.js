"use strict";
$(document).ready(function () {
    $("#datatable").DataTable();
    let exportDatatable = $("#datatable-buttons").DataTable({
        layout: {
            topStart: {
                buttons: ['copy', 'excel', 'print']
            }
        }
    });
    exportDatatable
        .buttons()
        .container()
        .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
        $("#datatable_length select[name*='datatable_length']").addClass("form-select form-select-sm"),
        $("#datatable_length select[name*='datatable_length']").removeClass("custom-select custom-select-sm"),
        $(".dataTables_length label").addClass("form-label");
});
