@extends('admin.layouts.app')
@section('title', 'Borrower Return')
@section('pageTitle', 'Borrower Return')
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
                    <th>Borrower </th>
                    <th>Date</th>
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
                        Borrower Return</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frm" enctype="multipart/form-data">
                        <input type="hidden" id="hidId" name="id" data-value-type="number" />
                        <div class="form-row">
                            <label for="drpBORROWER_ID" class="col-sm-3 col-form-label">Borrower</label>
                            <div class="form-group col-sm">
                                <select id="drpBORROWER_ID" name="BORROWER_ID"></select>
                            </div>
                        </div>
                        <div class=" form-row">
                            <label for="txtDate" class="col-sm-3 col-form-label">Date</label>
                            <div class="form-group col-sm">
                                <div class="input-group-prepend">
                                    <button type="button" id="toggle" class="input-group-text">
                                        <i class="fa fa-calendar-alt"></i>
                                    </button>
                                    <input type="text" class="form-control" id="txtDate" name="Date" maxlength="200"
                                        autocomplete="off">
                                </div>
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
        jQuery.datetimepicker.setDateFormatter('moment')
        $('#txtDate').datetimepicker({
            datepicker: true,
            timepicker: false,
            format: 'YYYY-MM-DD'
        })
        $('#toggle').on('click', function() {
            $('#txtDate').datetimepicker('toggle')
        })

        $(document).ready(function() {
            var tbl = $('#tbl').DataTable({
                // paging: false,
                // ordering: false,
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 2, 3]
                }],
                aaData: null,
                rowId: 'BORETURN_ID',
                columns: [{
                        data: null,
                        className: 'text-center'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<span>' + data.FirstName + ' ' + data.LastName + '</span>';
                        }
                    },
                    {
                        data: 'Date'
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
                AjaxGet(api_url + '/borrow_returns/get', function(result) {
                    tbl.clear().draw();
                    tbl.rows.add(result.data); // Add new data
                    tbl.columns.adjust().draw(); // Redraw the DataTable
                });
            }

            function bindTableEvents() {
                var rowId = 0;

                $('i[data-group=grpEdit]').off("click").click(function() {
                    rowId = $(this).closest('tr').attr('id');
                    AjaxGet(api_url + '/borrow_returns/get/' + rowId, function(result) {
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
                                AjaxPost(api_url + '/borrow_returns/delete/' + rowId, null, function(
                                    result) {
                                    if (result.error == 0) {
                                        tbl.row('#' + rowId).remove().draw();
                                        var content = 'Borrower Return' + ' "' + result.data
                                            .Date +
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

            loadBorrower();

            function loadBorrower() {
                $('#drpBORROWER_ID').val(null).empty().trigger('change');
                AjaxGet(api_url + '/borrowers/get', function(result) {
                    var optionData = [{
                        id: 0,
                        text: '------',
                        html: '----'
                    }];
                    $.each(result.data, function(i, el) {
                        optionData.push({
                            id: el.BORROWER_ID,
                            text: el.FirstName,
                            html: '<option class="">' + el.FirstName  + ' ' + el.LastName + '</option>'
                        });
                    });

                    $("#drpBORROWER_ID").select2({
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
                    Date: 'required',
                    BORROWER_ID: {
                        required: true,
                        min: 1
                    },

                },
                messages: {
                    Date: 'Please choose Date.',
                    BORROWER_ID: 'Please choose items.',
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
                    $('#hidId').val(infoData.BORETURN_ID);
                    $('#drpBORROWER_ID').val(infoData.BORROWER_ID);
                    $('#txtDate').val(infoData.Date);
                } else {
                    $('#modalAction').text('New');
                    $('#hidId').val('0');
                    $('#drpBORROWER_ID').val();
                    $('#txtDate').val('');


                }
            });

            $('#btnSaveModal').click(function() {
                if ($('#frm').valid()) {
                    // save data
                    data = $('#frm').serializeJSON();
                    id = $('#hidId').val();
                    if (id > 0) { // update
                        AjaxPost(api_url + '/borrow_returns/update', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Borrower Return has been updated successfully.'
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
                        AjaxPost(api_url + '/borrow_returns/add', data, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Borrower Returns has been added successfully.'
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
