@extends('admin.layouts.app')
@section('title', 'Permission')
@section('pageTitle', 'Permission')
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
                    <th style="width: 40px;">#</th>
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
                        Permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frm" enctype="multipart/form-data">
                        <input type="hidden" id="hidId" name="id" data-value-type="number" />
                        <div class=" form-row">
                            <label for="drpParentId" class="col-sm-4 col-form-label">Parent</label>
                            <div class="form-group col-sm">
                                <select id="drpParentId" name="PARENT_ID"></select>
                            </div>
                        </div>
                        <div class=" form-row">
                            <label for="txtName" class="col-sm-4 col-form-label">Permission name</label>
                            <div class="form-group col-sm">
                                <input type="text" class="form-control" id="txtName" name="Name" maxlength="200"
                                    placeholder="Name" autocomplete="off">
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
                // columnDefs: [{ orderable: false, targets: [0, 3] }],
                // aLengthMenu: [
                //     [10, 25, 50, 100, -1],
                //     [10, 25, 50, 100, '---']
                // ],
                // iDisplayLength: 50,
                // order: [[2, 'asc']],
                paging: false,
                ordering: false,
                aaData: null,
                rowId: 'PERMISSION_ID',
                columns: [{
                        data: null,
                        className: 'text-center'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<span class="ml_' + (data.Depth * 15) + '">' + data.Name +
                                '</span>';
                        }
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
                AjaxGet(api_url + '/permissions/get', function(result) {
                    tbl.clear().draw();
                    tbl.rows.add(result.data); // Add new data
                    tbl.columns.adjust().draw(); // Redraw the DataTable
                });
            }

            function bindTableEvents() {
                var rowId = 0;

                $('i[data-group=grpEdit]').off("click").click(function() {
                    rowId = $(this).closest('tr').attr('id');
                    AjaxGet(api_url + '/permissions/get/' + rowId, function(result) {
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
                                AjaxPost(api_url + '/permissions/delete/' + rowId, null, function(
                                    result) {
                                    if (result.error == 0) {
                                        tbl.row('#' + rowId).remove().draw();
                                        var content = 'Permissions' + ' "' + result.data.Name +
                                            '" has been deleted!';
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

            // ----------- select2 -----------------
            loadPermissions();

            function loadPermissions() {
                $('#drpParentId').val(null).empty().trigger('change');
                AjaxGet(api_url + '/permissions/get', function(result) {
                    var optionData = [{
                        id: 0,
                        text: '-----',
                        html: '-----'
                    }];
                    $.each(result.data, function(i, el) {
                        depth = el.Depth > 0 ? el.Depth + 2 : 0;
                        optionData.push({
                            id: el.PERMISSION_ID,
                            text: el.Name,
                            html: '<span class="ml-' + depth + '">' + el.Name + '</span>'
                        });
                    });

                    $("#drpParentId").select2({
                        dropdownParent: $('#editModal'),
                        width: '100%',
                        data: optionData,
                        escapeMarkup: function(markup) {
                            return markup;
                        },
                        templateResult: function(data) {
                            return data.html;
                        },
                        templateSelection: function(data) {
                            return data.text;
                        }
                    });
                });
            }
            // ----------- end: select2 ------------

            $('#btnAdd').click(function() {
                infoData = null;
                $('#editModal').modal('show');
            });

            var validator = $('#frm').validate({
                rules: {
                    Name: 'required',
                    PARENT_ID: 'required',

                },
                messages: {
                    Name: 'Please enter name.',
                    PARENT_ID: 'Please choose items.'

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
                    $('#hidId').val(infoData.PERMISSION_ID);
                    $('#txtName').val(infoData.Name);

                } else {
                    $('#modalAction').text('New');
                    $('#hidId').val();
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
                        AjaxPost(api_url + '/permissions/update', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Permissions has been updated successfully.'
                                });
                                $('#editModal').modal('hide');
                                loadTable();
                                loadPermissions();
                            } else {
                                PNotify.alert({
                                    title: 'Warning',
                                    text: res.message
                                });
                            }
                        });
                    } else { // add
                        AjaxPost(api_url + '/permissions/add', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Permissions has been added successfully.'
                                });
                                $('#editModal').modal('hide');
                                loadTable();
                                loadPermissions();
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
