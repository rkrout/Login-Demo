<?php 

$page_config = [
    "page_title" => "Happy Fox Api",
    "table_title" => "Report List",
    "show_add" => true
];

require("header.php");

?>

<div class="card-body">
    <div class="table-responsive"> 
        <table id="happytable" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Sort By</th>
                    <th>Submitted On</th>
                    <th>Submitted By</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Submit Report</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <form class="form-happy offcanvas-body">
            <div class="mb-4">
                <label for="daterange" class="form-label">Date Range</label>
                <input type="text" name="daterange" id="daterange" class="form-control" placeholder="Select a date range"/>
            </div>

            <div class="mb-4">
                <label for="SortBy" class="form-label">Sort By</label>
                <select class="form-control form-select" name="SortBy" id="SortBy" required>
                    <option value="client">Client</option>
                    <option value="assignee">Technician</option>
                    <option value="ticket">Ticket</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="OrderBy" class="form-label">Order By</label>
                <select class="form-control form-select" name="OrderBy" id="OrderBy" required>
                    <option value="a">Asc</option>
                    <option value="d">Desc</option>
                </select>
            </div>

            <input type="hidden" name="CreatedBy" value="<?= 1 ?>"/>

            <button class="btn w-100 btn-primary">Submit</button>
        </form>

        <div class="border border-bottom-2"></div>

        <div class="offcanvas-body">
            <div class="mb-4">
                <label for="reports" class="form-label">Reports</label>
                <select class="form-control form-select" name="reports" id="reports" required>
                </select>
            </div>
            <button disabled class="btn-reports btn btn-secondary w-100">Save</button>
        </div>
    </div>
</div>

<?php require("footer.php") ?>

<script>
    const table = $('#happytable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        responsive: true,
        columnDefs: [
            { width: "150px", orderable: false, targets: 6 }
        ],
        order: [
            [4, 'desc']
        ],
        ajax: '/happyfox/api_happyfox.php?action=select',
        columns: [
            { 
                data: 'StartDate',
            },
            { data: 'EndDate' },
            { data: 'SortBy',
                render: function (data, type, row, meta) {
                return data.charAt(0).toUpperCase() + data.slice(1);
                }, 
            },
            { data: 'Created',
                render: function (data, type, row, meta) {
                    var local = new Date(data);
                    var offset = local.getTimezoneOffset();
                    var utc = new Date(local.getTime() - offset * 60000);
                    return utc.toLocaleString('en-US');
                }, 
            },
            { data: 'FullName',
                render: function (data, type, row, meta) {
                    return (data) ? data : 'NA';
                }, 
            },
            { data: 'Status',
                render: function (data, type, row, meta) {
                    var status = '<span class="badge bg-warning">Submitted</span>'
                    if(data == 1){
                        status = `<span class="badge bg-secondary">In-Progress</span>`
                    } else if (data==2){
                        status= "<span class='badge bg-success'>Completed</span>"
                    }
                    return status
                }, 
            },
            { data: null,
                render: function (data, type, row, meta) {
                    let html = `
                        <button data-bs-name="tooltip" data-bs-title="Refresh" class="btn-refresh btn btn-primary btn-sm"
                            style="--bs-btn-padding-y: .15rem; --bs-btn-padding-x: .4rem; --bs-btn-font-size: .75rem;"
                        >
                            <i class='fa fa-refresh'></i>
                        </button>
                    `

                    if(row.Status == 2)
                    {
                        html = `
                            <div class="d-flex align-items-center gap-2">
                                <a data-bs-name="tooltip" data-bs-title="Download" href="download/${row.File}" target="_blank" class="btn btn-sm btn-success" name="download"
                                    style="--bs-btn-padding-y: .15rem; --bs-btn-padding-x: .4rem; --bs-btn-font-size: .75rem;"
                                >
                                    <i class="fa fa-download"></i>
                                </a>
                                <button data-bs-name="tooltip" data-bs-title="Delete" type="button" data-id="${row.Id}" data-file="${row.File}" class="btn-delete btn btn-sm btn-danger"
                                    style="--bs-btn-padding-y: .15rem; --bs-btn-padding-x: .4rem; --bs-btn-font-size: .75rem;"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `
                    }

                    return html
                },
            },
        ],
        "drawCallback": function(settings) {
        }
    })

    setInterval(function () {
        table.draw()
    }, 30000)

    $(".form-happy").submit(function(event){
        event.preventDefault()

        var dateRange = $("input[name=daterange]").val().split("-").map(date => date.trim());

        if(dateRange.length <= 1)
        {
            return alert("Please choose a date range")
        }

        const startDate = dateRange[0]

        const endDate = dateRange[1]

        var formData = new FormData($(this).get(0))
        formData.append("StartDate", startDate)
        formData.append("EndDate", endDate)

        $.ajax({
            url: "/happyfox/api_happyfox.php?action=insert",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                event.target.reset()
                table.draw()
                closeSidebar()
            }
        })
    });

    $(document).on("click", ".btn-refresh", function(){table.draw();});

    $(document).on("click", ".btn-delete", function(){
        var Id = $(this).attr("data-id")
        var File = $(this).data("file")

        if(confirm("Are you sure to delete this record and file?"))
        {
            const payload = new FormData()
            payload.append("Id", Id)
            payload.append("File", File)

            fetch("/happyfox/api_happyfox.php?action=delete", {
                method: "post",
                body: payload
            })
            .then(response => table.draw())
        }
    });

    function remove(Id, File)
    {
        if(confirm("Are you sure to delete this record and file?"))
        {
            $.post(
                "api_happyfox.php?action=delete",
                {
                    Id: Id, 
                    File:File
                }, 
                function(data, status)
                {
                    $('#happytable').DataTable.reload()
                }
            )
        }
    }

    $("input[name=daterange]").daterangepicker({ opens: "left",  autoUpdateInput: false, })

    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    })
    
    fetch("/happyfox/api_happyfox.php?action=get_all_reports")
        .then(response => response.json())
        .then(data => {
            const reportId = document.cookie.split(";").map(cookie => cookie.trim()).find(cookie => cookie.startsWith("reportId="))?.split("=")[1]

            const html = data.rows.map(item => `<option ${reportId == item.id ? "selected" : ""} value="${item.id}">${item.id} - ${item.name}</option>`).join("")
            
            $("select[id=reports]").html(html)

            if(!reportId)
            {
                document.cookie = `reportId=${$("select[name=reports]").val()}`
            }

            $(".btn-reports").attr("disabled", false)
        })

    
    $(".btn-reports").click(function() {
        document.cookie = `reportId=${$("select[name=reports]").val()}`
        
        closeSidebar()
    })

    function closeSidebar()
    {
        var myOffcanvas = document.querySelector('.offcanvas-end')
        var bsOffcanvas = bootstrap.Offcanvas.getInstance(myOffcanvas)
        bsOffcanvas.hide()
    }
</script>
