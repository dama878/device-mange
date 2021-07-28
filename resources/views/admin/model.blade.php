@extends('admin.layouts.app')
@section('title','Model')
@section('pageTitle','Model')
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
                        <th>Device Name </th>
                        <th>Model Name </th>
                        <th>Amount</th>
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
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Add Model</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <input type="hidden" id="hidId" name="id" data-value-type="number"/>

                    <div class=" form-row">
                        <label for="txtDevName" class="col-sm-3 col-form-label">Device TYPE</label>
                        <div class="form-group col-sm">
                            <select id="txtDevName" name="DEV_ID"></select>
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="txtModName" class="col-sm-3 col-form-label">Model name</label>
                        <div class="form-group col-sm">
                            <input type="text" class="form-control" id="txtModName" name="NameModel" maxlength="200" placeholder="Model name" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="txtAmount" class="col-sm-3 col-form-label">Amount</label>
                        <div class="form-group col-sm">
                            <input type="text" class="form-control" id="txtAmount" name="Amount" maxlength="8" placeholder="Amount" data-value-type="number" style="width: 120px"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="drpBorrowId" class="col-sm-3 col-form-label" >Multiple Borrow</label>
                        <div class="form-group col-sm">
                        <select class="select2" name="BOR_ID[]" id="drpBorrowId" multiple="multiple" style="width: 100%;">
                        </select>
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
            columnDefs: [{ orderable: false, targets: [0, 3] }],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '---']
            ],
            iDisplayLength: 50,
            order: [[2, 'asc']],
            aaData: null,
            rowId: 'MOD_ID',
            columns: [
                { data: null, className: 'text-center' },
                { data: 'DevName' },
                { data: 'NameModel' },
                { data: 'Amount' },
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
        function loadTable(){
            AjaxGet(api_url + '/models/get', function (result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }
        function  bindTableEvents(){
             var rowId = 0;

        $('i[data-group=grpEdit]').off("click").click(function () {
            rowId = $(this).closest('tr').attr('id');
            AjaxGet(api_url + '/models/get/' + rowId, function (result) {
                infoData = result.data;
                $('#editModal').modal('show');
            });
        });

        $('i[data-group=grpDelete]').off('click').click(function (e) {
            rowId = $(this).closest('tr').attr('id');
            $('#' + rowId).addClass('table-danger');
            ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function (yesClicked) {
                if (yesClicked) {
                    AjaxPost(api_url + '/models/delete/' + rowId, null, function (result) {
                        if (result.error == 0) {
                            tbl.row('#' + rowId).remove().draw();
                            var content = 'Model' + ' "' + result.data.Name + '" has been deleted!';
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
        $('#btnAdd').click(function(){
            infoData=null;
            $('#editModal').modal('show');
        });

        var validator = $('#frm').validate({
            rules: {
                DevName: 'required',
                NameModel: 'required',
                Amount: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                DevName: 'Please enter name.',
                NameModel: 'Please enter name.',
                Amount: {
                    required: 'Please enter number.',
                    digits: 'Number is invalid!.'
                }
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
        //select2
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


            loadDevices();
        function loadDevices() {
            $('#txtDevName').val(null).empty().trigger('change');
            AjaxGet(api_url+'/devices/get', function(result) {
                var optionData = [{ id: 0,text: 'Select device', html: 'Select device'}];
                $.each(result.data, function (i, el) {
                    
                    optionData.push({ id: el.DEV_ID, text: el.DevName, html: '<option class="">' + el.DevName + '</option>' });
                });

                $("#txtDevName").select2({
                    dropdownParent: $('#editModal'),
                    width: '100%',
                    data: optionData,
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    templateResult: function (data) {
                        return data.html;
                    },
                    templateSelection: function (data) {
                        return data.text;
                    }
                });
            });
        }
            //Endselect2

        // Edit 
        $('#editModal').modal({ show: false }).on('show.bs.modal', function (e) {
            validator.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');

                $('#hidId').val(infoData.MOD_ID);
                $('#txtDevName').val(infoData.DEV_ID).trigger('change');
                $('#txtModName').val(infoData.NameModel);
                $('#txtAmount').val(infoData.Amount);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#txtDevName').val('0').trigger('change');
                $('#txtModName').val('');
                $('#txtAmount').val('1');
            }
        }).on('shown.bs.modal', function () {
            $('#txtModName').focus();
        });

        $('#btnSaveModal').click(function(){
            if($('#frm').valid()){
                //save data
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if(id > 0){    //update date
                    AjaxPost(api_url + '/models/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Model has been updated successfully.'});
                            $('#editModal').modal('hide');
                            loadTable();
                        } else {
                            PNotify.alert({title: 'Warning', text: res.message});
                        }
                    });
                }else{ //add new data
                    AjaxPost(api_url + '/models/add', data, function(res) {                    
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Model has been added successfully.'});
                            $('#editModal').modal('hide');
                            loadTable();
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