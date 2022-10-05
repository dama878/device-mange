@extends('admin.layouts.app')
@section('title', 'Import Detail')
@section('pageTitle', 'Import Detail Page')
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
                <th>Import </th>
                <th>Model</th>
                <th>Unit</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Note</th>
                <th style="width: 40px;">Active</th>
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
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Import Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <input type="hidden" id="hidId" name="id" data-value-type="number" />
                    <div class="form-group form-row">
                        <label for="drpImportId" class="col-sm-3 col-form-label">Import: </label>
                        <div class="col-sm">
                            <select id="drpImportId" name="IMP_ID"></select>
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label for="drpModelId" class="col-sm-3 col-form-label">Model </label>
                        <div class="col-sm">
                            <select id="drpModelId" name="MOD_ID"></select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtUnit" class="col-sm-3 col-form-label">Unit</label>
                        <div class="col-sm">
                            <select name="Unit" class="custom-select" id="txtUnit">
                                <option value="0"> Bộ </option>
                                <option value="1"> Cái </option>
                                <option value="2"> Máy </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtType" class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm">
                            <select name="Type" class="custom-select" id="txtType">
                                <option value="0"> Auth </option>
                                <option value="1"> Fake 1 </option>
                                <option value="2"> 2hand </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtQuantity" class="col-sm-3 col-form-label">Quantity</label>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="txtQuantity" name="Quantity" maxlength="3">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtPrice" class="col-sm-3 col-form-label">Price</label>
                        <div class="col-sm">
                            <input type="number " class="form-control" id="txtPrice" name="Price" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtNote" class="col-sm-3 col-form-label">Note</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtNote" name="Note" maxlength="255" placeholder="Unit">
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
                targets: [0, 8]
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
            rowId: 'IMPDE_ID',
            columns: [{
                    data: null,
                    className: 'text-center'
                },
                {
                    data: 'Invoice'
                },
                {
                    data: 'MOD_ID'
                },
                {
                    data: 'Unit'
                },
                {
                    data: 'Type'
                },
                {
                    data: 'Quantity'
                },
                {
                    title: 'Price',
                    data: 'Price',
                    className: "text-center",
                    render: $.fn.dataTable.render.number(',', '.', 0, '')
                },
                {
                    data: 'Note'
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
            AjaxGet(api_url + '/import-details/get', function(result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }

        function bindTableEvents() {
            var rowId = 0;

            $('i[data-group=grpEdit]').off("click").click(function() {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/import-details/get/' + rowId, function(result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function(e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function(yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/import-details/delete/' + rowId, null, function(result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Import Detail has been deleted!';
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
        loadImports();

        function loadImports() {
            $('#drpImportId').val(null).empty().trigger('change');
            AjaxGet(api_url + '/imports/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: '-----',
                    html: '-----'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.IMP_ID,
                        text: el.Invoice,
                        html: '<span>' + el.Invoice + '</span>'
                    });
                });

                $("#drpImportId").select2({
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


        // ----------- select: model -----------------
        loadModels();

        function loadModels() {
            $('#drpModelId').val(null).empty().trigger('change');
            AjaxGet(api_url + '/models/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: '-----',
                    html: '-----'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.MOD_ID,
                        text: el.NameModel,
                        html: '<span>' + el.NameModel + '</span>'
                    });
                });

                $("#drpModelId").select2({
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
                IMP_ID: 'required',

            },
            messages: {
                IMP_ID: 'Please choose .',
                Quatity: 'Please enter at most 3 numbers',
                Price: 'Please enter at most 10 numbers',
            }
        });

        $('#editModal').modal({
            show: false
        }).on('show.bs.modal', function(e) {
            validator.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');

                $('#hidId').val(infoData.IMPDE_ID);
                $('#drpImportId').val(infoData.IMP_ID);
                $('#txtUnit').val(infoData.Unit);
                $('#txtType').val(infoData.Type);
                $('#txtQuantity').val(infoData.Quantity);
                $('#txtPrice').val(infoData.Price);
                $('#txtNote').val(infoData.Note);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#drpImportId').val('');
                $('#txtUnit').val('');
                $('#txtType').val('');
                $('#txtQuantity').val('');
                $('#txtPrice').val('');
                $('#txtNote').val('');
            }
        }).on('shown.bs.modal', function() {
            $('#txtUnit').focus();
        });

        $('#btnSaveModal').click(function() {
            if ($('#frm').valid()) {
                // save data
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/import-details/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Import detail has been updated successfully.'
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
                    AjaxPost(api_url + '/import-details/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Import detail has been added successfully.'
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