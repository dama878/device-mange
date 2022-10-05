@extends('admin.layouts.app')
@section('title', 'Import')
@section('pageTitle', 'Import')
@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="card-tools">
            <button id="btnAdd" type="button" class="btn btn-primary"><i class="fas fa-plus">Add</i> </button>
            <button id="btnAddDetail" type="button" class="btn btn-success"><i class="fas fa-plus">Add Detail</i> </button>
        </div>
    </div>
    <div class="card-body">
        <table id="tbl" class="table table-hover">
            <thead>
                <th style="width: 40px;">#</th>
                <th></th>
                <th>Invoice</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Depot</th>
                <th>Place</th>
                <th>Import</th>
                <th style="width: 40px;"></th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<!--Import-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Import</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                            <input type="hidden" id="hidId" name="id" data-value-type="number" />
                            <!-- hóa đơn -->
                            <div class="form-group form-row">
                                <label for="txtInvoice" class="col-sm-3 col-form-label">Invoice</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="txtInvoice" name="Invoice" maxlength="50" placeholder="Invoice">
                                </div>
                            </div>
                            <!-- ngày nhạp -->
                            <div class="form-group form-row">
                                <label for="txtDate" class="col-sm-3 col-form-label">Date</label>
                                <div class="col-sm md-form md-outline input-with-post-icon datepicker">
                                    <input placeholder="Select date" type="text" id="txtDate" name="Date" class="form-control">
                                </div>
                            </div>
                            <!-- người nhập -->
                            <div class="form-group form-row">
                                <label for="drpCustomerId" class="col-sm-3 col-form-label">Customer </label>
                                <div class="col-sm">
                                    <select id="drpCustomerId" name="CUS_ID"></select>
                                </div>
                            </div>
                            <!-- kho chứa -->
                            <div class="form-group form-row">
                                <label for="txtDepot" class="col-sm-3 col-form-label">Depot</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="txtDepot" name="Depot" maxlength="50" placeholder="Depot" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="txtPlace" class="col-sm-3 col-form-label">Place</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="txtPlace" name="Place" maxlength="100" placeholder="Place" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="txtImport" class="col-sm-3 col-form-label">Import</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="txtImport" name="Import">
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <input type="hidden" id="hidIdDetail" name="id" data-value-type="number" />
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
<!-- Detail-->
<div class="modal fade" id="editModalDetail" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Import Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frmDetail" enctype="multipart/form-data">
                    <input type="hidden" id="hidIdDetail" name="id" data-value-type="number" />
                    <div class="form-group form-row">
                        <label for="drpImportId" class="col-sm-3 col-form-label">Import: </label>
                        <div class="col-sm">
                            <select id="drpImportId" name="IMP_ID"></select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="drpModelIdDetail" class="col-sm-3 col-form-label">Model </label>
                        <div class="col-sm">
                            <select id="drpModelIdDetail" name="MOD_ID"></select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtUnitDetail" class="col-sm-3 col-form-label">Unit</label>
                        <div class="col-sm">
                            <select name="Unit" class="custom-select" id="txtUnitDetail">
                                <option value="0"> Bộ </option>
                                <option value="1"> Cái </option>
                                <option value="2"> Máy </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtTypeDetail" class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm">
                            <select name="Type" class="custom-select" id="txtTypeDetail">
                                <option value="0"> Auth </option>
                                <option value="1"> Fake 1 </option>
                                <option value="2"> 2hand </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtQuantityDetail" class="col-sm-3 col-form-label">Quantity</label>
                        <div class="col-sm">
                            <input type="number" class="form-control" id="txtQuantityDetail" name="Quantity" maxlength="3">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtPriceDetail" class="col-sm-3 col-form-label">Price</label>
                        <div class="col-sm">
                            <input type="number " class="form-control" id="txtPriceDetail" name="Price" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtNoteDetail" class="col-sm-3 col-form-label">Note</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtNoteDetail" name="Note" maxlength="255" placeholder="Unit">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnSaveModalDetail" class="btn btn-primary">Save</button>
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
                [2, 'asc']
            ],
            aaData: null,
            rowId: 'IMP_ID',
            columns: [{
                    data: null,
                    className: 'text-center'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<i data-group="grpDetail" class="fas fa-plus-circle text-success pointer mr-1"></i>';
                    }
                },
                {
                    data: 'Invoice'
                },
                {
                    data: 'Date'
                },
                {
                    data: 'FullName'
                },
                {
                    data: 'Depot'
                },
                {
                    data: 'Place'
                },
                {
                    data: 'Import'
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

        // load table
        function loadTable() {
            AjaxGet(api_url + '/imports/get', function(result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }

        function bindTableEvents() {
            var rowId = 0;
            $('i[data-group=grpDetail]').on("click").click(function() {
                var id = $(this).closest('tr').attr('id');
                var tr = $(this).closest('tr');
                var row = tbl.row(tr);
                if (row.child.isShown()) {
                    // This row is already open - close it
                    destroyChild(row);
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    createChild(row, id);
                    tr.addClass('shown');
                }
            });

            $('i[data-group=grpEdit]').off("click").click(function() {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/imports/get/' + rowId, function(result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function(e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function(yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/imports/delete/' + rowId, null, function(result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Import has been deleted!';
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
        //
        function destroyChild(row) {
            var table = $("table", row.child());
            table.detach();
            table.DataTable().destroy();
            // And then hide the row
            row.child.hide();
        }

        function createChild(row, id) {

            // This is the table we'll convert into a DataTable
            var table = $('<table class="display table table-info" width="100%"/>');
            // Display it the child row
            row.child(table).show();
            // Initialise as a DataTable
            var usersTable = table.DataTable({
                ajax: {
                    url: api_url + '/import-details/get-by-id/' + id,
                    type: 'get',
                    data: function(d) {
                        d.site = id;
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0, 5, 6]
                }],
                order: [
                    [1, 'asc']
                ],
                rowId: 'IMPDE_ID',
                aaData: null,
                // dom: 'Bfrtip',
                // buttons: [{
                //     text: '+ Add Detail',
                //     className: "btn btn-primary",
                //     action: function(e, dt, node, config) {
                //         infoData = null;
                //         $('#editModalDetail').modal('show');
                //     }
                // }],
                columns: [{
                        title: '#',
                        data: 'IMPDE_ID',
                        className: 'text-center'
                    },
                    // {
                    //     title: 'Import ID',
                    //     data: 'IMP_ID'
                    // },
                    {
                        title: 'Model Name',
                        data: 'NameModel'
                    },
                    {
                        title: 'Unit',
                        data: 'Unit'
                    },
                    {
                        title: 'Type',
                        data: 'Type'
                    },
                    {
                        title: 'Quantity',
                        data: 'Quantity'
                    },
                    {
                        title: 'Price',
                        data: 'Price',
                        className: "text-center",
                        render: $.fn.dataTable.render.number(',', '.', 0, '')
                    },
                    {
                        title: 'Note',
                        data: 'Note'
                    },
                    {
                        title: 'Action',
                        data: null,
                        render: function(data, type, row) {
                            return '<i data-group="grpEditDetail" class="fas fa-edit text-info pointer mr-1"></i>' +
                                '<i data-group="grpDeleteDetail" class="far fa-trash-alt text-danger pointer"></i>';
                        }
                    }
                ],
                select: true,
                drawCallback: function(settings) {
                    bindTableEventsDetail();
                },
                rowCallback: function(row, data, iDisplayIndex) {
                    var api = this.api();
                    var info = api.page.info();
                    var index = (info.page * info.length + (iDisplayIndex + 1));
                    $('td:eq(0)', row).html(index);
                }
            });
        }

        function bindTableEventsDetail() {
            var rowId = 0;
            $('i[data-group=grpEditDetail]').off("click").click(function() {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/import-details/get/' + rowId, function(result) {
                    infoData = result.data;
                    $('#editModalDetail').modal('show');
                });
            });
            $('i[data-group=grpDeleteDetail]').off('click').click(function(e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function(yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/import-details/delete/' + rowId, null, function(result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Item has been deleted!';
                                loadTable();
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
        //-----------------end datimepicker-------------------

        //-----load data from customer table------
        loadCustomers();

        function loadCustomers() {
            $('#drpCustomerId').val(null).empty().trigger('change');
            AjaxGet(api_url + '/customers/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: '-----',
                    html: '-----'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.CUS_ID,
                        text: el.FullName,
                        html: '<span>' + el.FullName + '</span>'
                    });
                });

                $("#drpCustomerId").select2({
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

        // Add new import
        $('#btnAdd').click(function() {
            infoData = null;
            $('#editModal').modal('show');
        });

        //check imput value
        var validator = $('#frm').validate({
            rules: {
                Invoice: {
                    required: true
                },
                CUS_ID: {
                    required: true
                },
                Date: {
                    required: true
                },
                Import: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                Invoice: {
                    required: 'Please enter invoice.',
                },
                CUS_ID: {
                    required: 'Please enter customer.',
                },
                Date: {
                    required: 'Please enter date.',
                },
                Import: {
                    required: 'Please enter number.',
                    digits: 'Number is invalid!.'
                }
            }
        });

        $('#editModal').modal({
            show: false
        }).on('show.bs.modal', function(e) {
            validator.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');
                $('#hidId').val(infoData.IMP_ID);
                $('#txtInvoice').val(infoData.Invoice);
                $('#txtDate').val(infoData.Date);
                $('#drpCustomerId').val(infoData.CUS_ID);
                $('#txtDepot').val(infoData.Depot);
                $('#txtPlace').val(infoData.Place);
                $('#txtImport').val(infoData.Import);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#txtInvoice').val('');
                $('#txtDate').val('');
                $('#drpCustomerId').val('');
                $('#txtDepot').val('');
                $('#txtPlace').val('');
                $('#txtImport').val('');
            }
        }).on('shown.bs.modal', function() {
            $('#drpCustomerId').focus();
        });

        $('#btnSaveModal').click(function() {
            if ($('#frm').valid()) {
                // save data
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/imports/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Import has been updated successfully.'
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
                    AjaxPost(api_url + '/imports/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Import has been added successfully.'
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

        // load database from model table
        loadModels();

        function loadModels() {
            $('#drpModelId').val(null).empty().trigger('change');
            AjaxGet(api_url + '/models/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: 'Select model',
                    html: 'Select model'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.MOD_ID,
                        text: el.NameModel,
                        html: '<option class="">' + el.NameModel + '</option>'
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

        // Validation import detail
        var validatorDetail = $('#frmDetail').validate({
            rules: {
                IMP_ID: {
                    required: true
                },
                Quantity: {
                    required: true,
                    digits: true
                },
                Price: {
                    required: true,
                    digits: true
                },
            },
            messages: {
                IMP_ID: {
                    required: 'Please enter invoice.',
                },
                Quantity: {
                    required: 'Please enter at most 3 numbers',
                },
                Price: {
                    required: 'Please enter at most 10 numbers',
                },
            },
        });

        //-------select imports to details------------
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
                    dropdownParent: $('#editModalDetail'),
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
        //-- -- -- -- -- - end: imports-- -- -- -- -- --

        //----- load data to detail-----
        loadModelsToDetail();

        function loadModelsToDetail() {
            $('#drpModelIdDetail').val(null).empty().trigger('change');
            AjaxGet(api_url + '/models/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: 'Select model',
                    html: 'Select model'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.MOD_ID,
                        text: el.NameModel,
                        html: '<option class="">' + el.NameModel + '</option>'
                    });
                });
                $("#drpModelIdDetail").select2({
                    dropdownParent: $('#editModalDetail'),
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

        //Add Detail
        $('#btnAddDetail').click(function() {
            infoData = null;
            $('#editModalDetail').modal('show');
            loadModelsToDetail();
            loadImports();
        });

        //Edit Detail
        $('#editModalDetail').modal({
            show: false
        }).on('show.bs.modal', function(e) {
            // validatorDetail.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');
                $('#hidIdDetail').val(infoData.IMPDE_ID);
                //$('#hidIdImp').val(infoData.IMP_ID);
                $('#drpModelIdDetail').val(infoData.MOD_ID).trigger('change');
                $('#drpImportId').val(infoData.IMP_ID).trigger('change');
                $('#txtUnitDetail').val(infoData.Unit);
                $('#txtTypeDetail').val(infoData.Type);
                $('#txtQuantityDetail').val(infoData.Quantity);
                $('#txtPriceDetail').val(infoData.Price);
                $('#txtNoteDetail').val(infoData.Note);
            } else {
                $('#modalAction').text('New');
                // $('#hidIdImp').val('0');
                $('#hidIdDetail').val('0');
                $('#drpModelIdDetail').val('0').trigger('change');
                $('#drpImportId').val('0').trigger('change');
                $('#txtUnitDetail').val('');
                $('#txtTypeDetail').val('');
                $('#txtQuantityDetail').val('');
                $('#txtPriceDetail').val('');
                $('#txtNoteDetail').val('');
            }
        });

        //Save Detail
        $('#btnSaveModalDetail').click(function() {
            if ($('#frmDetail').valid()) {
                // save data
                data = $('#frmDetail').serializeJSON();
                id = $('#hidIdDetail').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/import-details/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Detail has been updated successfully.'
                            });
                            $('#editModalDetail').modal('hide');
                            loadTable();
                            loadModelsToDetail();
                            loadImports();
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
                                text: 'Detail has been added successfully.'
                            });
                            $('#editModalDetail').modal('hide');
                            loadTable();
                            loadModelsToDetail();
                            loadImports();
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