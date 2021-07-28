@extends('admin.layouts.app')
@section('title','Details Borrow')
@section('pageTitle','Details Borrow')
@section('content')
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="card-tools">
                    <button id="btnAdd" type="button" class="btn btn-primary "><i class="fas fa-plus"> Add</i></button>
                </div>
            </div>
            <div class="card-body">
                <table id="tbl" class="table table-bordered table-hover table-triped">
                    <thead>
                        <th style="width: 40px">#</th>
                        <th>Borrower Name</th>
                        <th>Model Name</th>
                        <th>Borrow Date</th>
                        <th>Borrower Return</th>
                        <th>Due Date Return</th>
                        <th>Date Return</th>
                        <th>Is Renew</th>
                        <th style="width: 60px"></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
@endsection
@section('footer')
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Details Borrow</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <input type="hidden" id="hidId" name="id" data-value-type="number"/>
                    <div class="form-row">
                    <label for="drpModelId" class="col-sm-4 col-form-label">Model Name</label>
                    <div class="form-group col-sm">
                    <select id="drpModelId" name="MOD_ID"class="form-control">
                    </select>
                    </div>
                    </div>
                  <!-- <input type="hidden" id="hidIdBor" name="id" data-value-type="number"/> -->
                    <div class="form-row">
                    <label for="drpBorrowId" class="col-sm-4 col-form-label" >Borrow Date</label>
                    <div class="form-group col-sm">
                    <select id="drpBorrowId" name="BOR_ID" class="form-control">
                    </select>
                    </div>
                    </div>

                    <div class="form-row">
                        <label for="drpBoReturn" class="col-sm-4 col-form-label" >Borrower Return</label>
                        <div class="form-group col-sm">
                        <select id="drpBoReturn" name="BORETURN_ID" class="form-control">
                        </select>
                        </div>
                        </div>

                    <div class="form-row">
                        <label for="txtDueDate" class="col-sm-4 col-form-label">Due Date Return</label>
                        <div class="form-group col-sm">
                            <input type="text" class="form-control" id="txtDueDate" name="DueDateReturn" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="txtDateReturn" class="col-sm-4 col-form-label">Date Return</label>
                        <div class="form-group col-sm">
                            <input type="text" class="form-control" id="txtDateReturn" name="DateReturn" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="chkIsRenew" class="col-sm-4 col-form-label">Is Renew</label>
                        <div class="form-group col-sm">
                            <input type="checkbox" class="form-control" id="chkIsRenew" name="IsRenew" maxlength="200" placeholder="Is Renew" autocomplete="off">
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
        $(document).ready(function(){
        var tbl= $('#tbl').DataTable({
            columnDefs: [{ orderable: false, targets: [0, 7, 8] }],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '---']
            ],
            iDisplayLength: 50,
            order: [[1, 'asc']],
            aaData: null,
            rowId: 'BORDE_ID',
            columns: [
                { data: null, className: 'text-center'},
                { data: null,  render: function ( data, type, row ) {
                    return '<span>' + data.FirstName + ' ' + data.LastName +'</span>';
                }},
                { data: 'NameModel' },
                { data: 'Date' },
                { data: 'BORETURN_ID' },
                { data: 'DueDateReturn' },
                { data: 'DateReturn' },
                { data: 'IsRenew' },
                { data: null,  render: function ( data, type, row ) {
                    return '<i data-group="grpEdit" class="fas fa-edit text-info pointer mr-1"></i>' +
                        '<i data-group="grpDelete" class="far fa-trash-alt text-danger pointer"></i>';
                }}
            ],
            initComplete: function (settings, json) {
                loadTable();
            },
            drawCallback: function (settings) {
                bindTableEvents();
            },
            rowCallback: function( row, data, iDisplayIndex ) {
                var api = this.api();
                var info = api.page.info();
                var index = (info.page * info.length + (iDisplayIndex + 1));
                $('td:eq(0)', row).html(index);
            }
        });
        //load Table
        function loadTable(){
            AjaxGet(api_url + '/borrowdetailmodels/get', function (result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }  

        function  bindTableEvents(){
            var rowId = 0;
            $('i[data-group=grpEdit]').off("click").click(function () {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/borrowdetailmodels/get/' + rowId, function (result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function (e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function (yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/borrowdetailmodels/delete/' + rowId, null, function (result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Detail Borrow' + ' "' + result.data.Name + '" has been deleted!';
                                PNotify.success({title: 'Info', text: content});
                            } else {
                                PNotify.alert({title: 'Warning', text: result.message});
                            }
                        }, function (jqXHR) {
                            PNotify.error({title: 'Error', text: jqXHR.responseText});
                        });
                    }
                    $('#' + rowId).removeClass('table-danger');
                });
            });
        }

        var validator = $('#frm').validate({
            rules: {
                MOD_ID  : {
                required: true,
                min: 1
            },

            BOR_ID  :{
                required: true,
                min: 1
            },
            BORETURN_ID  :{
                required: true,
                min: 1
            },
            DueDateReturn  :{
                required: true,
            },

            DateReturn  :{
                required: true,
            },
            },
            messages: {
                MOD_ID  : {
                min: "Please select name model"
            },
            BOR_ID  : {
                min: "Please select date borrow"
            },
            BORETURN_ID  : {
                min: "Please select date borrower return"
            },
            DueDateReturn  : {
                required: "Please select due date return"
            },
            DateReturn  : {
                required: "Please select date return"
            },
            },

            errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            }
        });
    
        // ----------- select2 -----------------
        loadModels();
        function loadModels() {
                $('#drpModelId').val(null).empty().trigger('change');
                AjaxGet(api_url + '/models/get', function(result) {
                    var optionData = [{
                        id: 0,
                        text: '------',
                        html: '----'
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

            loadBorrows();
        function loadBorrows() {
                $('#drpBorrowId').val(null).empty().trigger('change');
                AjaxGet(api_url + '/borrows/get', function(result) {
                    var optionData = [{
                        id: 0,
                        text: '------',
                        html: '----'
                    }];
                    $.each(result.data, function(i, el) {
                        optionData.push({
                            id: el.BOR_ID,
                            text: el.Date,
                            html: '<option class="">' + el.Date + '</option>'
                        });
                    });

                    $("#drpBorrowId").select2({
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


            loadBorrowerReturns();
        function loadBorrowerReturns() {
            $('#drpBoReturn').val(null).empty().trigger('change');
            AjaxGet(api_url + '/borrow_returns/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: '------',
                    html: '----'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.BORETURN_ID,
                        text: el.Date,
                        html: '<option class="">' + el.Date + '</option>'
                    });
                });

                $("#drpBoReturn").select2({
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


        //-----------------datimepicker-------------------
        jQuery.datetimepicker.setDateFormatter('moment')
            $('#txtDueDate').datetimepicker({
                timepicker: true,
                datepicker: true,
                hours12: false,
                step: 15,
                format: 'YYYY-MM-DD HH:mm',
            })
            $('#toggle').on('click', function () {
                $('#txtDueDate').datetimepicker('toggle')
            })

            jQuery.datetimepicker.setDateFormatter('moment')
            $('#txtDateReturn').datetimepicker({
                timepicker: true,
                datepicker: true,
                hours12: false,
                step: 15,
                format: 'YYYY-MM-DD HH:mm',
            })
            $('#toggle').on('click', function () {
                $('#txtDateReturn').datetimepicker('toggle')
            })
        //-----------------end datimepicker-------------------

        //Edit
        $('#editModal').modal({ show: false }).on('show.bs.modal', function (e) {
        // validator.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');
                $('#hidId').val(infoData.BORDE_ID);
                $('#drpModelId').val(infoData.MOD_ID);
                $('#drpBorrowId').val(infoData.BOR_ID);
                $('#drpBoReturn').val(infoData.BORETURN_ID);
                $('#txtDueDateReturn').val(infoData.DueDateReturn);
                $('#txtDateReturn').val(infoData.DateReturn);
                $('#chkIsRenew').bootstrapSwitch('state', infoData.IsRenew);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val(0);
                $('#drpModelId').val('0');
                $('#drpBorrowId').val('0');
                $('#drpBoReturn').val('0');
                $('#txtDueDateReturn').val('');
                $('#txtDateReturn').val('');
                $('#chkIsRenew').bootstrapSwitch('state',true);
            }
        }).on('shown.bs.modal', function () {
            $('#txtDueDateReturn').focus();
        });        

        //Add
         $('#btnAdd').click(function(){
                    infoData=null;
                    $('#editModal').modal('show');
                    loadModels();
                    loadBorrows();
                    loadBorrowerReturns();
                });
        //Save
        $('#btnSaveModal').click(function(){
            if ($('#frm').valid()) {
                // save data
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/borrowdetailmodels/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Detail borrow has been updated successfully.'});
                            $('#editModal').modal('hide');
                            loadTable();
                            loadModels();
                            loadBorrows();
                            loadBorrowerReturns();

                        } else {
                            PNotify.alert({title: 'Warning', text: res.message});
                        }
                    });
                } else { // add
                    AjaxPost(api_url + '/borrowdetailmodels/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Detail Borrow has been added successfully.'});
                            $('#editModal').modal('hide');
                            loadTable();
                            loadModels();
                            loadBorrows();
                            loadBorrowerReturns();

                        } else {
                            PNotify.alert({title: 'Warning', text: res.message});
                        }
                    });
                }
            }
        });
    });
</script>
@endsection