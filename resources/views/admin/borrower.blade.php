@extends('admin.layouts.app')
@section('title', 'Borrower')
@section('pageTitle', 'Borrower')
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
                    <th>Borrower Group</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Image</th>
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
                        Borrower</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frm" enctype="multipart/form-data">
                        <input type="hidden" id="hidId" name="id" data-value-type="number" />
                        <div class=" form-row">
                            <label for="drpBOGROUP_ID" class="col-sm-3 col-form-label">BG</label>
                            <div class=" form-group col-sm">
                                <select id="drpBOGROUP_ID" name="BOGROUP_ID"></select>
                            </div>
                        </div>
                        <div class=" form-row">
                            <label for="txtFirstName" class="col-sm-3 col-form-label">First Name</label>
                            <div class="form-group col-sm">
                                <input type="text" class="form-control" id="txtFirstName" name="FirstName" maxlength="200"
                                    placeholder="First Name" autocomplete="off">
                            </div>
                        </div>
                        <div class=" form-row">
                            <label for="txtLastName" class="col-sm-3 col-form-label">Last Name</label>
                            <div class="form-group col-sm">
                                <input type="text" class="form-control" id="txtLastName" name="LastName" maxlength="200"
                                    placeholder="Last Name" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="txtPhone" class="col-sm-3 col-form-label">Phone</label>
                            <div class="form-group col-sm">
                                <input type="text" class="form-control" id="txtPhone" name="Phone" maxlength="200"
                                    placeholder="Phone" autocomplete="off">
                            </div>
                        </div>
                        <div class=" form-row">
                            <label for="txtEmail" class="col-sm-3 col-form-label">Email</label>
                            <div class="form-group col-sm">
                                <input type="text" class="form-control" id="txtEmail" name="Email" maxlength="200"
                                    placeholder="Email" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="txtNote" class="col-sm-3 col-form-label">Note</label>
                            <div class="form-group col-sm">
                                {{-- <input type="text" class="form-control" id="txtNote" name="Note" maxlength="200"
                                    placeholder="Note" autocomplete="off"> --}}
                                <textarea type="text" class="form-control" id="txtNote" name="Note" placeholder="Note"
                                    autocomplete="off"> </textarea>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label for="txtFile" class="col-sm-3 col-form-label">Image</label>
                            <div class="col-sm">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="txtFile" name="Image"
                                        autocomplete="off">
                                    <label class="custom-file-label" id="txtFileLabel" for="txtFile"
                                        data-browse="Browse">Upload image</label>
                                </div>
                                <img id="imgPreview" src="" class="mt-2 img-fluid" />
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
                // columnDefs: [{
                //     orderable: false,
                //     targets: [0, 3]
                // }],
                // aLengthMenu: [
                //     [10, 25, 50, 100, -1],
                //     [10, 25, 50, 100, '---']
                // ],
                // iDisplayLength: 50,
                // order: [
                //     [2, 'asc']
                // ],
                // paging: false,
                // ordering: false,
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 6, 7]
                }],
                aaData: null,
                rowId: 'BORROWER_ID',
                columns: [{
                        data: null,
                        className: 'text-center'
                    },
                    {
                        data: 'Name'
                    },
                    {
                        data: 'FirstName'
                    },
                    {
                        data: 'LastName'
                    },
                    {
                        data: 'Phone'
                    },
                    {
                        data: 'Email'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<img class="img-fluid" src="' + base_url +
                                '/public/data/banners/' + data.Image +
                                '" style="height: 80px; width:100px"/>';
                        }
                    },
                    // {
                    //     data: 'Note'
                    // },
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
                AjaxGet(api_url + '/borrowers/get', function(result) {
                    tbl.clear().draw();
                    tbl.rows.add(result.data); // Add new data
                    tbl.columns.adjust().draw(); // Redraw the DataTable
                });
            }

            $("#txtFile").change(function(e) {
                ReadImageUrl(this, "imgPreview", "txtFileLabel");
            });

            function bindTableEvents() {
                var rowId = 0;

                $('i[data-group=grpEdit]').off("click").click(function() {
                    rowId = $(this).closest('tr').attr('id');
                    AjaxGet(api_url + '/borrowers/get/' + rowId, function(result) {
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
                                AjaxPost(api_url + '/borrowers/delete/' + rowId, null, function(
                                    result) {
                                    if (result.error == 0) {
                                        tbl.row('#' + rowId).remove().draw();
                                        var content = 'Borrower' + ' "' + result.data
                                            .FirstName +
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

            loadBorrowerGroups();

            function loadBorrowerGroups() {
                $('#drpBOGROUP_ID').val(null).empty().trigger('change');
                AjaxGet(api_url + '/borrower_groups/get', function(result) {
                    var optionData = [{
                        id: 0,
                        text: '------',
                        html: '----'
                    }];
                    $.each(result.data, function(i, el) {
                        optionData.push({
                            id: el.BOGROUP_ID,
                            text: el.Name,
                            html: '<option class="">' + el.Name + '</option>'
                        });
                    });

                    $("#drpBOGROUP_ID").select2({
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
                    BOGROUP_ID: {
                        required: true,
                        min: 1
                    },
                    FirstName: 'required',
                    LastName: 'required',
                    Phone: {
                        required: true,
                        digits: true
                    },
                    Email: 'required',
                    Note: 'required'

                },
                messages: {
                    BOGROUP_ID: 'Please choose Borrow Group ID.',
                    FirstName: 'Please enter First Name.',
                    LastName: 'Please enter Last Name.',
                    Phone: {
                        required: 'Please enter number.',
                        digits: 'Number is invalid!.'
                    },
                    Email: 'Please enter Email.',
                    Note: 'Please enter Note.'


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
                    $('#hidId').val(infoData.BORROWER_ID);
                    $('#drpBOGROUP_ID').val(infoData.BOGROUP_ID).trigger('change');
                    $('#txtFirstName').val(infoData.FirstName);
                    $('#txtLastName').val(infoData.LastName);
                    $('#txtPhone').val(infoData.Phone);
                    $('#txtEmail').val(infoData.Email);
                    $('#txtNote').val(infoData.Note);
                    $("#txtFileLabel").text(infoData.Image);
                    if (infoData.Image != null)
                        $("#imgPreview").attr('src', base_url + '/public/data/banners/' + infoData.Image);
                    else {
                        $("#txtFileLabel").text('Upload image');
                        $("#imgPreview").attr('src', '');
                    }
                } else {
                    $('#modalAction').text('New');
                    $('#hidId').val('0');
                    $('#drpBOGROUP_ID').val('0').trigger('change');
                    $('#txtFirstName').val('');
                    $('#txtLastName').val('');
                    $('#txtPhone').val('');
                    $('#txtEmail').val('');
                    $('#txtNote').val('');
                    $("#txtFileLabel").text('Upload image');
                    $("#imgPreview").attr('src', '');

                }
            }).on('shown.bs.modal', function() {
                $('#txtFirstName').focus();
            });

            $('#btnSaveModal').click(function() {
                if ($('#frm').valid()) {
                    // save data
                    var form = $("#frm")[0];
                    var formdata = new FormData(form); // high importance!
                    id = $('#hidId').val();
                    if (id > 0) { // update
                        AjaxPostForm(api_url + '/borrowers/update', formdata, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Borrower has been updated successfully.'
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
                        AjaxPostForm(api_url + '/borrowers/add', formdata, function(res) {
                            if (res.error == 0) {
                                PNotify.success({
                                    title: 'Info',
                                    text: 'Borrower has been added successfully.'
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
