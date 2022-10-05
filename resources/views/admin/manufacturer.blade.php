@extends('admin.layouts.app')
@section('title', 'Mnufacturer')
@section('pageTitle', 'Manufacturer')
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
                    <th>Adress</th>
                    <th>Note</th>
                    {{-- <th>Display Order</th> --}}
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
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Manufacturer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <input type="hidden" id="hidId" name="id" data-value-type="number"/>
                    <div class=" form-row">
                        <label for="txtManName" class=" col-sm-3 col-form-label">Manufacturer Name</label>
                        <div class="col-sm form-group">
                            <input type="text" class="form-control" id="txtManName" name="ManName" maxlength="200" placeholder="Manufacturer Name" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtAddress" class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtAddress" name="Address" maxlength="200" placeholder="Address" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtNote" class="col-sm-3 col-form-label">Note</label>
                        <div class="col-sm">
                            <textarea rows="5" class="form-control" id="txtNote" name="Note" maxlength="200" placeholder="Note" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class=" form-row">
                        <label for="txtDisplayOrder" class="col-sm-3 col-form-label">Display order</label>
                        <div class="col-sm form-group">
                            <input type="number" min="1" max="9" onkeydown="if(parseInt(this.value)>9){ this.value =9; return false; }" class="form-control " id="txtDisplayOrder" name="DisplayOrder" placeholder="Display order" data-value-type="number" style="width: 80px"/>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="chkIsPublished" class="col-sm-3 col-form-label">Is Published</label>
                        <div class="col-sm">
                            <input type="checkbox" id="chkIsPublished" name="IsPublished" data-off-color="danger" data-on-color="primary" value="true">
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
        var tbl = $('#tbl').DataTable({
            columnDefs: [{ orderable: false, targets: [0,2,3] }],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '---']
            ],
            iDisplayLength: 50,
            order: [1, 'asc'],
            aaData: null,
            rowId: 'MAN_ID',
            columns: [
                { data: null, className: 'text-center' },
                { data: 'ManName',className: 'text-center' },
                { data: 'Address',className: 'text-center' },
                { data: 'Note',className: 'text-center' },
                // { data: 'DisplayOrder',className: 'text-center' },
                { data: null,  render: function ( data, type, row ) {
                    return '<i data-group="grpEdit" class="fas fa-edit text-info pointer mr-3"></i>' +
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
        function loadTable() {
            AjaxGet(api_url + '/manufacturers/get', function (result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }
        function bindTableEvents() {
            var rowId = 0;

            $('i[data-group=grpEdit]').off("click").click(function () {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/manufacturers/get/' + rowId, function (result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function (e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function (yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/manufacturers/delete/' + rowId, null, function (result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'manufacturers' + ' "' + result.data.ManName + '" has been deleted!';
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
            infoData = null;
            $('#editModal').modal('show');
        });

        var validator = $('#frm').validate({
            rules: {
                ManName: {
                    required: true,
                },
                DisplayOrder: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                ManName: {
                    required: 'Please enter Mannufacturer',
                },
                DisplayOrder: {
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

        $('#editModal').modal({ show: false }).on('show.bs.modal', function (e) {
            validator.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');

                $('#hidId').val(infoData.MAN_ID);
                $('#txtManName').val(infoData.ManName);
                $('#txtAddress').val(infoData.Address);
                $('#txtNote').val(infoData.Note);
                $('#txtDisplayOrder').val(infoData.DisplayOrder);
                $('#chkIsPublished').bootstrapSwitch('state', infoData.IsPublished);
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#txtManName').val('');
                $('#txtAddress').val('');
                $('#txtNote').val('');
                $('#txtDisplayOrder').val('1');
                $('#chkIsPublished').bootstrapSwitch('state', true);
            }
        }).on('shown.bs.modal', function () {
            $('#txtManName').focus();
        });

        $('#btnSaveModal').click(function(){
            if ($('#frm').valid()) {
                // save data
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/manufacturers/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Manufacturer has been updated successfully.'});
                            $('#editModal').modal('hide');
                            loadTable();
                        } else {
                            PNotify.alert({title: 'Warning', text: res.message});
                        }
                    });
                } else { // add
                    AjaxPost(api_url + '/manufacturers/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Manufacturer has been added successfully.'});
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