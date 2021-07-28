@extends('admin.layouts.app')
@section('title', 'User')
@section('pageTitle', 'User')
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
                    <th>Full Name</th>
                    
                    <th>username</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Date Of Birth</th>
                   
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
                <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frm" enctype="multipart/form-data">
                    <input type="hidden" id="hidId" name="id" data-value-type="number"/>
                   
                    <div class=" form-row">
                        <label for="drpROLE_ID" class="col-sm-3 col-form-label">ROLE ID</label>
                        <div class="form-group col-sm">
                            <select id="drpROLE_ID" name="ROLE_ID"></select>
                        </div>
                    </div>
                    <div class=" form-row">
                        <label for="txtUsername" class="col-sm-3 col-form-label">username</label>
                        <div class="col-sm form-group">
                            <input type="text" class="form-control" id="txtUsername" name="username" maxlength="200" placeholder="Address" autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="form-group form-row">
                        <label for="txtFirstName" class="col-sm-3 col-form-label">Full name</label>
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-sm-7 ">
                                    <input type="text" class="form-control" id="txtFirstName" name="FirstName" maxlength="200" placeholder="First Name" autocomplete="off">
                                </div>
                                <div class="col-sm-5 ">
                                    <input type="text" class="form-control" id="txtLastName" name="LastName" maxlength="200" placeholder="Last Name" autocomplete="off">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                                           
                                             
                         

                    <div class=" form-row">
                        <label for="txtpassword" class="col-sm-3 col-form-label">password</label>
                        <div class="col-sm form-group">
                            <input type="password" class="form-control  @error('password') is-invalid @enderror" id="txtpassword" name="password" maxlength="200" placeholder="Password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class=" form-row">
                        <label for="txtConPassword" class="col-sm-3 col-form-label">Confirm password</label>
                        <div class="col-sm form-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"  id="txtpassword" name="password_confirmation" maxlength="200" placeholder="password_confirmation" required autocomplete="current-password">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtGender" class="col-sm-3 col-form-label">Gender</label>
                        <div class="col-sm">
                            <div class="d-inline mr-5">
                                <input type="radio" id="radioMaleGender" name="Gender" value="1">
                                <label for="radioMaleGender">
                                    Male
                                </label>
                            </div>
                            <div class="d-inline">
                                <input type="radio" id="radioFemaleGender" name="Gender" value="0">
                                <label for="radioFemaleGender">
                                    Female
                                </label>
                            </div>
                        </div>
                    </div>
        

                    <div class="form-group form-row">
                        <label for="txtAddress" class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="txtAddress" name="Address" maxlength="200" placeholder="Address" autocomplete="off">
                        </div>
                    </div>
                    <div class=" form-row">
                        <label for="txtEmail" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm form-group">
                            <input type="email" class="form-control" id="txtEmail" name="Email" maxlength="200" placeholder="Email" autocomplete="off">
                        </div>
                    </div>  
                    <div class=" form-row">
                        <label for="txtPhone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm form-group">
                            <input type="text" class="form-control" id="txtPhone" name="Phone" maxlength="200" placeholder="Phone" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtDOB" class="col-sm-3 col-form-label">Date of Birth</label>
                        <div class="col-sm input-group">
                            <input type="text" class="form-control" id="txtDOB" name="DOB" placeholder="date of birth">
                            <div class="input-group-prepend">
                                <button type="button" id="toggle" class="input-group-text">
                                    <i class="fa fa-calendar-alt"></i>
                                </button>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="txtDate" class="col-sm-3 col-form-label">Date</label>
                        <div class="col-sm input-group">
                            <input type="text" class="form-control" id="txtDate" name="Date" placeholder="Date">
                            <div class="input-group-prepend">
                                <button type="button" id="toggle1" class="input-group-text">
                                    <i class="fa fa-calendar-alt"></i>
                                </button>
                            </div>
                            
                        </div>
                    </div>
                    <div class=" form-row">
                        <label for="txtStatus" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm form-group">
                            <input type="number" min="1" max="9" onkeydown="if(parseInt(this.value)>9){ this.value =9; return false; }" class="form-control" id="txtStatus" name="Status" autocomplete="off">
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
            columnDefs: [{ orderable: false, targets: [0,3, 4,5,6] },],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '---']
            ],
            iDisplayLength: 50,
            order: [[1, 'asc'], [2, 'asc']],
            aaData: null,
            rowId: 'USE_ID',
            columns: [
                { data: null, className: 'text-center' },
                { data: null,  render: function ( data, type, row ) {
                    return '<span>' + data.FirstName + ' ' + data.LastName +  '</span>';
                },className: 'text-center'},
                {data: 'username',className: 'text-center'},
                { data: 'Phone' ,className: 'text-center'},
                { data: 'Email' ,className: 'text-center'},
                { data: 'DOB' ,className: 'text-center'},
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
            AjaxGet(api_url + '/users/get', function (result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }
        function bindTableEvents() {
            var rowId = 0;

            $('i[data-group=grpEdit]').off("click").click(function () {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/users/get/' + rowId, function (result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function (e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function (yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/users/delete/' + rowId, null, function (result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'users' + ' "' + result.data.ManName + '" has been deleted!';
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

//--------------------select2-----------------
        loadRoles();
        function loadRoles() {
            $('#drpROLE_ID').val(null).empty().trigger('change');
            AjaxGet(api_url+'/roles/get', function(result) {
                var optionData = [{ id: 0,text: '------', html: '----'}];
                $.each(result.data, function (i, el) {
                    
                    optionData.push({ id: el.ROLE_ID, text: el.RoleName, html: '<option class="">' + el.RoleName + '</option>' });
                });

                $("#drpROLE_ID").select2({
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
//--------------------end:select2-----------------


//--------------------date-------------------------


$('#txtDOB').datetimepicker({
                        timepicker: false,
                        datepicker: true,
                        hours12: false,
                        
                        format: 'Y-m-d',

                        
                    })
                    $('#toggle').on('click', function () {
                        $('#txtDOB').datetimepicker('toggle')
                     })
                   
                    $('#txtDate').datetimepicker({
                        timepicker: false,
                        datepicker: true,
                        hours12: false,
                        
                        format: 'Y-m-d',
                        
                    })
                    $('#toggle1').on('click', function () {
                        $('#txtDate').datetimepicker('toggle')
                     })

//----------------end:date----------------


        $('#btnAdd').click(function(){
            infoData = null;
            $('#editModal').modal('show');
        });

        var validator = $('#frm').validate({
            rules: {
               ROLE_ID : {
                    required: true,
                },
                password : {
                    required: true,
                    minlength: 6
                },
                username:{
                    required: true,
                },
                Phone:{
                    required: true,
                },
                Status:{
                    required: true,
                    digits: true,
                }
            },
            messages: {
                Status:{
                    digits: 'Number is invalid!.',
                    required: 'Please enter number.',
                },
                ROLE_ID: 'Please enter Type.',
                Phone : {
                    required: 'Please enter your number phone',
                },
                password : {
                    required: 'Please enter Password',
                    minlength: 'Your password must be at least 6 characters long',
                    confirm: 'The password confirmation does not match'
                },
                username: {
                    required : 'Please enter User Name'
                },
                Status:{
                    required: 'Please enter Status',
                    maxlength: 'Your status max is 1 number long',
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

                $('#hidId').val(infoData.USE_ID);
                
                $('#drpROLE_ID').val(infoData.ROLE_ID);
                $('#txtUsername').val(infoData.username);
                $('#txtFirstName').val(infoData.FirstName);
                $('#txtLastName').val(infoData.LastName);
                $('#txtpassword').val('');
                $('#txtGender').val(infoData.Gender);
                $('#txtAddress').val(infoData.Address);
                $('#txtEmail').val(infoData.Email);
                $('#txtPhone').val(infoData.Phone);
                $('#txtDOB').val(infoData.DOB);
                $('#txtDate').val(infoData.Date);
                $('#txtStatus').val(infoData.Status);
                
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                
                $('#drpROLE_ID').val('');
                $('#txtUsername').val('');
                $('#txtFirstName').val('');
                $('#txtLastName').val('');
                $('#txtpassword').val('');
                $('#txtGender').val('');
                $('#txtAddress').val('');
                $('#txtEmail').val('');
                $('#txtPhone').val('');
                $('#txtDOB').val('');
                $('#txtDate').val('');
                $('#txtStatus').val('');
               
                
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
                    AjaxPost(api_url + '/users/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'User has been updated successfully.'});
                            $('#editModal').modal('hide');
                            loadTable();
                        } else {
                            PNotify.alert({title: 'Warning', text: res.message});
                        }
                    });
                } else { // ad
                    console.log(data);
                    AjaxPost(api_url + '/users/add', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'User has been added successfully.'});
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