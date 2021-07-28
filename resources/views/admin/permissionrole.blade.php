@extends('admin.layouts.app')
@section('title', 'Permission Role')
@section('pageTitle', 'Permission Role')
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
                    <th>Permission </th>
                    <th>Role</th>
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
                        Fine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frm" enctype="multipart/form-data">
                        <input type="hidden" id="hidId" name="id" data-value-type="number" />
                        <div class="form-row">
                            <label for="drpPERMISSION_ID" class="col-sm-3 col-form-label">Permission</label>
                            <div class="form-group col-sm">
                                <select id="drpPERMISSION_ID" name="PERMISSION_ID"></select>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="drpROLE_ID" class="col-sm-3 col-form-label">Role</label>
                            <div class="form-group col-sm">
                                <select id="drpROLE_ID" name="ROLE_ID"></select>
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
                // paging: false,
                // ordering: false,
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 3]
                }],
                aaData: null,
                rowId: 'PERO_ID',
                columns: [{
                        data: null,
                        className: 'text-center'
                    },
                    {
                        data: 'Name'
                    },
                    {
                        data: 'RoleName'
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
                AjaxGet(api_url + '/permission_roles/get', function(result) {
                    tbl.clear().draw();
                    tbl.rows.add(result.data); // Add new data
                    tbl.columns.adjust().draw(); // Redraw the DataTable
                });
            }

            function bindTableEvents() {
                var rowId = 0;

                $('i[data-group=grpEdit]').off("click").click(function() {
                    rowId = $(this).closest('tr').attr('id');
                    AjaxGet(api_url + '/permission_roles/get/' + rowId, function(result) {
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
                                AjaxPost(api_url + '/permission_roles/delete/' + rowId, null, function(
                                    result) {
                                    if (result.error == 0) {
                                        tbl.row('#' + rowId).remove().draw();
                                        var content = 'Permission Role' + ' "' + result.data
                                            .PERO_ID +
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



            loadPermission();

            function loadPermission() {
                $('#drpPERMISSION_ID').val(null).empty().trigger('change');
                AjaxGet(api_url + '/permissions/get', function(result) {
                    var optionData = [{
                        id: 0,
                        text: '------',
                        html: '----'
                    }];
                    $.each(result.data, function(i, el) {
                        optionData.push({
                            id: el.PERMISSION_ID,
                            text: el.Name,
                            html: '<option class="">' + el.Name + '</option>'
                        });
                    });

                    $("#drpPERMISSION_ID").select2({
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

            loadRole();

            function loadRole() {
                $('#drpROLE_ID').val(null).empty().trigger('change');
                AjaxGet(api_url + '/roles/get', function(result) {
                    var optionData = [{
                        id: 0,
                        text: '------',
                        html: '----'
                    }];
                    $.each(result.data, function(i, el) {
                        optionData.push({
                            id: el.ROLE_ID,
                            text: el.RoleName,
                            html: '<option class="">' + el.RoleName + '</option>'
                        });
                    });

                    $("#drpROLE_ID").select2({
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




            $('#btnAdd').click(function() {
                infoData = null;
                $('#editModal').modal('show');
            });

            var validator = $('#frm').validate({
                rules: {
                    PERMISSION_ID: {
                        required: true,
                        min: 1
                    },
                    ROLE_ID: {
                        required: true,
                        min: 1
                    }
                },
                messages: {
                    PERMISSION_ID: 'Please choose items.',
                    ROLE_ID: 'Please choose items.'


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
                    $('#hidId').val(infoData.PERO_ID);
                    $('#drpPERMISSION_ID').val(infoData.PERMISSION_ID);
                    $('#drpROLE_ID').val(infoData.ROLE_ID);

                } else {
                    $('#modalAction').text('New');
                    $('#hidId').val('0');
                    $('#drpPERMISSION_ID').val();
                    $('#drpROLE_ID').val();



                }
            }).on('shown.bs.modal', function() {
                $('#hidId').focus();
            });

            $('#btnSaveModal').click(function() {
                if ($('#frm').valid()) {
                    // save data
                    data = $('#frm').serializeJSON();
                    id = $('#hidId').val();
                    if (id > 0) { // update
                        AjaxPost(api_url + '/permission_roles/update', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Permission Role has been updated successfully.'
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
                        AjaxPost(api_url + '/permission_roles/add', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Permission Role has been added successfully.'
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
