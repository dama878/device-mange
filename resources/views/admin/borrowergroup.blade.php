@extends('admin.layouts.app')
@section('title', 'Borrower Group')
@section('pageTitle', 'Borrower Group')
@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <div class="card-tools">
                <button id="btnAdd" type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </div>
        </div>
        <div class="card-body">
            <table id="tbl" class="table table-bordered table-hover table-striped">
                <thead>
                    <th style="width: 60px;">#</th>
                    <th>Name</th>
                    <th style="width: 60px"></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection
@section('footer')
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span>
                        Borrower Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frm" enctype="multipart/form-data">
                        <input type="hidden" id="hidId" name="id" data-value-type="number" />
                        <div class=" form-row">
                            <label for="txtName" class="col-sm-3 col-form-label">Name:</label>
                            <div class="form-group col-sm">
                                <input type="text" class="form-control" id="txtName" name="Name" maxlength="200"
                                    placeholder="Borrower Group Name" autocomplete="off">
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
                // Cột 0, 2 không có sắp xếp
                columnDefs: [{
                    orderable: false,
                    targets: [0, 2]
                }],
                aLengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, '---']
                ],
                iDisplayLength: 50,
                // Sắp xếp tăng dần ở cột 1 asc
                order: [
                    [1, 'asc']
                ],
                aaData: null,
                rowId: 'BOGROUP_ID',
                columns: [{
                        data: null,
                        className: 'text-center'
                    },
                    {
                        data: 'Name'
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
                AjaxGet(api_url + '/borrower_groups/get', function(result) {
                    tbl.clear().draw();
                    tbl.rows.add(result.data); // Add new data
                    tbl.columns.adjust().draw(); // Redraw the DataTable
                });
            }

            function bindTableEvents() {
                var rowId = 0;

                $('i[data-group=grpEdit]').off("click").click(function() {
                    rowId = $(this).closest('tr').attr('id');
                    AjaxGet(api_url + '/borrower_groups/get/' + rowId, function(result) {
                        infoData = result.data;
                        $('#editModal').modal('show');
                    });
                });

                $('i[data-group=grpDelete]').off('click').click(function(e) {
                    rowId = $(this).closest('tr').attr('id');
                    $('#' + rowId).addClass('table-danger');
                    ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes',
                        'No',
                        function(yesClicked) {
                            if (yesClicked) {
                                AjaxPost(api_url + '/borrower_groups/delete/' + rowId, null, function(
                                    result) {
                                    if (result.error == 0) {
                                        tbl.row('#' + rowId).remove().draw();
                                        var content = 'Borrower Group' + ' "' + result.data
                                            .Name + '" has been deleted!';
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
            $('#btnAdd').click(function() {
                infoData = null;
                $('#editModal').modal('show');
            });
            var validator = $('#frm').validate({
                rules: {
                    Name: 'required'
                },
                messages: {
                    Name: 'Please enter borrower group name.'
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('#editModal').modal({
                show: false
            }).on('show.bs.modal', function(e) {
                validator.resetForm();
                // check infoData edit or add new
                if (infoData != null) {
                    $('#modalAction').text('Update');
                    $('#hidId').val(infoData.BOGROUP_ID);
                    $('#txtName').val(infoData.Name);
                } else {
                    $('#modalAction').text('New');
                    $('#hidId').val('0');
                    $('#txtName').val('');
                }
            }).on('shown.bs.modal', function() {
                $('#txtName').focus();
            });

            $('#btnSaveModal').click(function() {
                if ($('#frm').valid()) {
                    // save data
                    data = $('#frm').serializeJSON();
                    id = $('#hidId').val();
                    if (id > 0) { // update
                        AjaxPost(api_url + '/borrower_groups/update', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Borrower Group has been updated successfully.'
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
                        AjaxPost(api_url + '/borrower_groups/add', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Borrower Group has been added successfully.'
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
