const onDatatable = (name, is_add, lengthDisplay = 10, padding = "pb-2") => {
    $(name).DataTable({
        autoWidth: false,
        responsive: true,
        order: [],
        stateSave: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 },
        ],
        lengthMenu: [
            [lengthDisplay, 25, 50],
            [lengthDisplay, 25, 50],
        ],
        dom: `<"row justify-between ${padding}"<"col-12 mt-2"f>><"datatable-wrap radius-10 mb-4 rounded-0 border-0"t><"row"<"col-12"p>>`,
        language: {
            search: "",
            searchPlaceholder: "Search",
            lengthMenu: "<div class='d-flex mt-3 align-items-center'><span class='d-none d-sm-inline-block cms-dataTable_length--font me-3'>Items per page: </span><div class='container_select'> _MENU_ </div></div>",
            info: "<span class='cms-dataTable_length--font'>_START_ -_END_ of _TOTAL_ Entries</span>",
            infoEmpty: "No records found",
            infoFiltered: "( Total _MAX_  )",
            paginate: {
                first: "First",
                last: "Last",
                next: "<em class='icon mdi mdi-arrow-right-thin regular-font fw-bold pointer'></em>",
                previous: "<em class='icon mdi mdi-arrow-left-thin regular-font fw-bold pointer'></em>",
            },
        },
    });

    if (is_add) {
        $(".dt-search").addClass("pl-2 d-flex justify-content-between align-items-center").prepend('<button type="button" class="btn btn-sm table-button text-white d-inline small-font py-2 px-3 fw-medium new_record fw-bold align-left">New Record</button>');

        // remove the label element from dt-search children
        $(".dt-search label").remove();
    }
};

export { onDatatable };
