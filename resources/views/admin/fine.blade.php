@extends('admin.layouts.app')
@section('title', 'Fine')
@section('pageTitle', 'Fines')
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
            <th class="text-center">Money</th>
            <th>Reason</th>
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
                Fine</h5>
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
                    <label for="txtMoney" class="col-sm-3 col-form-label">Money</label>
                    <div class="form-group col-sm ">
                        <input maxlength="12" type="number" class="form-control " id="txtMoney" name="Money"
                            autocomplete="off">
                    </div>
                </div>
                <div class=" form-row">
                    <label for="txtReason" class="col-sm-3 col-form-label">Reason</label>
                    <div class="form-group col-sm">
                        {{-- <input type="text" class="form-control" id="txtReason" name="Reason" maxlength="200"
                                    autocomplete="off"> --}}
                        <textarea type="text" class="form-control" id="txtReason" name="Reason"
                            autocomplete="off"> </textarea>
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
        timepicker: true,
        datepicker: true,
        hours12: false,
        step: 15,
        format: 'YYYY-MM-DD HH:mm',
    })
    $('#toggle').on('click', function() {
        $('#txtDate').datetimepicker('toggle')
    })

    $(document).ready(function() {
        var tbl = $('#tbl').DataTable({
            // paging: false,
            // ordering: false,
            order: [
                [4, 'desc']
            ],
            columnDefs: [{
                orderable: false,
                targets: [0, 5]
            }],
            aaData: null,
            rowId: 'FINE_ID',
            columns: [{
                    data: null,
                    className: 'text-center'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<span>' + data.LastName + ' ' + data.FirstName + '</span>';
                    }
                },
                {
                    data: 'Money',
                    className: "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 0, '')
                },
                {
                    data: 'Reason'
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
            AjaxGet(api_url + '/fines/get', function(result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }

        function bindTableEvents() {
            var rowId = 0;

            $('i[data-group=grpEdit]').off("click").click(function() {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/fines/get/' + rowId, function(result) {
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
                            AjaxPost(api_url + '/fines/delete/' + rowId, null, function(
                                result) {
                                if (result.error == 0) {
                                    tbl.row('#' + rowId).remove().draw();
                                    var content = 'Fines' + ' "' + result.data
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
                        html: '<option class="">' + el.FirstName + '</option>'
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
                BORROWER_ID: {
                    required: true,
                    min: 1
                },
                Money: {
                    required: true,
                    digits: true
                },

                Reason: 'required',
                Date: 'required'

            },
            messages: {
                BORROWER_ID: 'Please choose items.',
                Money: {
                    required: 'Please enter number.',
                    digits: 'Number is invalid!.'
                },
                Reason: 'Please enter reason.',
                Date: 'Please choose Date.'
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
                $('#hidId').val(infoData.FINE_ID);
                $('#drpBORROWER_ID').val(infoData.BORROWER_ID).trigger('change');
                $('#txtMoney').val(infoData.Money);
                $('#txtReason').val(infoData.Reason);
                $('#txtDate').val(infoData.Date);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#drpBORROWER_ID').val();
                $('#txtMoney').val(numeral(1000).format('0,0'));
                $('#txtReason').val('');
                $('#txtDate').val(moment().format("YYYY-MM-DD HH:mm:ss"));


            }
        }).on('shown.bs.modal', function() {
            $('#txtReason').focus();
        });

        $('#btnSaveModal').click(function() {
            if ($('#frm').valid()) {
                // save data
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/fines/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Fine has been updated successfully.'
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
                    AjaxPost(api_url + '/fines/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Fine has been added successfully.'
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
