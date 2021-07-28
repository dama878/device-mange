@extends('admin.layouts.app')
@section('title', 'Customer')
@section('pageTitle', 'Customer')
@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="card-tools">
            <button id="btnAdd" type="button" class="btn btn-primary"><i class="fas fa-plus">Add</i> </button>
        </div>
    </div>
    <div class="card-body">
        <table id="tbl" class="table table-hover">
            <thead>
                <th style="width: 40px;">#</th>
                <th>FullName</th>
                <th>Daty Of Birth</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th style="width: 40px;"></th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <input type="hidden" id="hidId" name="id" data-value-type="number" />

                    <div class="form-group form-row">
                        <label for="txtFullName" class="col-sm-3 col-form-label">Full Name</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtFullName" name="FullName" maxlength="50" placeholder="Full Name">
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label for="txtDayOfBirth" class="col-sm-3 col-form-label">Day Of Birth</label>
                        <div class="col-sm md-form md-outline input-with-post-icon datepicker">
                            <input placeholder="Select date" type="text" id="txtDayOfBirth" name="DayOfBirth" class="form-control">
                        </div>
                    </div>

                    <!-- kho chứa -->
                    <div class="form-group form-row">
                        <label for="txtPhone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtPhone" name="Phone" maxlength="50" placeholder="Phone">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtEmail" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtEmail" name="Email" maxlength="100" placeholder="Place" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtAddress" class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtAddress" name="Address">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnSaveModal" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        var tbl = $('#tbl').DataTable({
            columnDefs: [{
                orderable: false,
                targets: [0, 6]
            }],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '---']
            ],
            iDisplayLength: 50,
            order: [
                [1, 'asc']
            ],
            aaData: null,
            rowId: 'CUS_ID',
            columns: [{
                    data: null,
                    className: 'text-center'
                },
                {
                    data: 'FullName'
                },
                {
                    data: 'DayOfBirth'
                },
                {
                    data: 'Phone'
                },
                {
                    data: 'Email'
                },
                {
                    data: 'Address'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<i data-group="grpEdit" class="fas fa-edit text-info pointer mr-1"></i>' +
                            '<i data-group="grpDelete" class="far fa-trash-alt text-danger pointer"></i>';
                    }
                }
            ],
            initComplete: function(settings, json) {
                loadTable();
            },
            drawCallback: function(settings) {
                bindTableEvents();
            },
            rowCallback: function(row, data, iDisplayIndex) {
                var api = this.api();
                var info = api.page.info();
                var index = (info.page * info.length + (iDisplayIndex + 1));
                $('td:eq(0)', row).html(index);
            }
        });

        function loadTable() {
            AjaxGet(api_url + '/customers/get', function(result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }

        function bindTableEvents() {
            var rowId = 0;

            $('i[data-group=grpEdit]').off("click").click(function() {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/customers/get/' + rowId, function(result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function(e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function(yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/customers/delete/' + rowId, null, function(result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Customer has been deleted!';
                                PNotify.success({
                                    title: 'Info',
                                    text: content
                                });
                            } else {
                                PNotify.alert({
                                    title: 'Warning',
                                    text: result.message
                                });
                            }
                        }, function(jqXHR) {
                            PNotify.error({
                                title: 'Error',
                                text: jqXHR.responseText
                            });
                        });
                    }
                    $('#' + rowId).removeClass('table-danger');
                });
            });
        }




        //-----------------datimepicker-------------------
        jQuery.datetimepicker.setDateFormatter('moment')
        $('#txtDayOfBirth').datetimepicker({

            datepicker: true,

            format: 'YYYY-MM-DD',
        })
        $('#toggle').on('click', function() {
            $('#txtDayOfBirth').datetimepicker('toggle')
        })
        //-----------------end datimepicker-------------------

        $('#btnAdd').click(function() {
            infoData = null;
            $('#editModal').modal('show');
        });

        var validator = $('#frm').validate({
            rules: {
                FullName: {
                    required: true
                },

            },
            messages: {
                FullName: {
                    required: 'Please enter name.',
                },

            }
        });

        $('#editModal').modal({
            show: false
        }).on('show.bs.modal', function(e) {
            validator.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');

                $('#hidId').val(infoData.CUS_ID);
                $('#txtFullName').val(infoData.FullName);
                $('#txtDayOfBirth').val(infoData.DayOfBirth);
                $('#txtPhone').val(infoData.Phone);
                $('#txtEmail').val(infoData.Email);
                $('#txtAddress').val(infoData.Address);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#txtFullName').val('');
                $('#txtDayOfBirth').val('');
                $('#txtPhone').val('');
                $('#txtEmail').val('');
                $('#txtAddress').val('');
            }
        }).on('shown.bs.modal', function() {
            $('#txtFullName').focus();
        });

        $('#btnSaveModal').click(function() {
            if ($('#frm').valid()) {
                // save data
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/customers/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Customer has been updated successfully.'
                            });
                            $('#editModal').modal('hide');
                            loadTable();
                        } else {
                            PNotify.alert({
                                title: 'Warning',
                                text: res.message
                            });
                        }
                    });
                } else { // add
                    AjaxPost(api_url + '/customers/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Customer has been added successfully.'
                            });
                            $('#editModal').modal('hide');
                            loadTable();
                        } else {
                            PNotify.alert({
                                title: 'Warning',
                                text: res.message
                            });
                        }
                    });
                }
            }
        });
    });
</script>

@endsection