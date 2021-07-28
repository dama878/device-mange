@extends('admin.layouts.app')
@section('title', 'Export')
@section('pageTitle', 'Export')
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
                <th>Invoice</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Depot</th>
                <th>Place</th>
                <th>Export</th>
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
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Export</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <input type="hidden" id="hidId" name="id" data-value-type="number" />
                    <!-- hóa đơn -->
                    <div class="form-group form-row">
                        <label for="txtInvoice" class="col-sm-3 col-form-label">Invoice</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtInvoice" name="Invoice" maxlength="50" placeholder="Invoice">
                        </div>
                    </div>

                    <!-- ngày xuất -->
                    <div class="form-group form-row">
                        <label for="txtDate" class="col-sm-3 col-form-label">Date</label>
                        <div class="col-sm md-form md-outline input-with-post-icon datepicker">
                            <input placeholder="Select date" type="text" id="txtDate" name="Date" class="form-control">

                        </div>
                    </div>

                    <!-- người xuất -->
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
                        <label for="txtExport" class="col-sm-3 col-form-label">Export</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtExport" name="Export">
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
                targets: [0, 7]
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
            rowId: 'EXP_ID',
            columns: [{
                    data: null,
                    className: 'text-center'
                },
                {
                    data: 'Invoice'
                },
                {
                    data: 'Date'
                },
                {
                    data: 'CUS_ID'
                },
                {
                    data: 'Depot'
                },
                {
                    data: 'Place'
                },
                {
                    data: 'Export'
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
            AjaxGet(api_url + '/exports/get', function(result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }

        function bindTableEvents() {
            var rowId = 0;

            $('i[data-group=grpEdit]').off("click").click(function() {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/exports/get/' + rowId, function(result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function(e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function(yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/exports/delete/' + rowId, null, function(result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Export has been deleted!';
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
        // ----------- end: select2 ------------


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


        $('#btnAdd').click(function() {
            infoData = null;
            $('#editModal').modal('show');
        });

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
                Export: {
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
                Export: {
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

                $('#hidId').val(infoData.EXP_ID);
                $('#txtInvoice').val(infoData.Invoice);
                $('#txtDate').val(infoData.Date);
                $('#drpCustomerId').val(infoData.CUS_ID);
                $('#txtDepot').val(infoData.Depot);
                $('#txtPlace').val(infoData.Place);
                $('#txtExport').val(infoData.Export);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#txtInvoice').val('');
                $('#txtDate').val('');
                $('#drpCustomerId').val('');
                $('#txtDepot').val('');
                $('#txtPlace').val('');
                $('#txtExport').val('');
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
                    AjaxPost(api_url + '/exports/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Export has been updated successfully.'
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
                    AjaxPost(api_url + '/exports/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({
                                title: 'Info',
                                text: 'Export has been added successfully.'
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