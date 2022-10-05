@extends('admin.layouts.app')
@section('title','Borrow')
@section('pageTitle','Borrow')
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
                     <!--   <th style="width: 20px"></th> -->
                        <th>Borrower Name</th>
                        <th>Date Borrow</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
@endsection
@section('footer')
    {{-- Modal borrow --}}
    <div class="modal fade " id="editModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                 <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Borrow</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                       <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                        <form method="post" id="frm" enctype="multipart/form-data">
                            <input type="hidden" id="hidId" name="id" data-value-type="number"/>
                            <div class=" form-row">
                                <label for="drpBorrowerId" class="col-sm-4 col-form-label">Borrower Name </label>
                                <div class="form-group col-sm">
                                    <select id="drpBorrowerId" name="BORROWER_ID"></select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="Date" class="col-sm-4 col-form-label">Date borrow</label>
                                <div class="col-sm">
                                    <input type="text" name="Date" id="txtDate" class="form-control" 
                                    maxlength="200" placeholder="Enter date" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    
                 <!--   <div class="modal-content">                   
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span>Add Detail</h5> 
                            <button type="button" class="btn btn-success" data-dismiss="modal">Add Detail</button>
                        </div>
                    -->
                   <div class="col-sm-8">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="text-success">
                                    <i class="fas fa-list-ul "></i>
                                    Borrow Model Detail
                                </h4>
                            </div>
                            <div class="col-sm-6">
                                <button id="btnAddModelDetail" type="button" class="float-right btn btn-success ml-2">
                                <i class="fas fa-plus mr-2"></i>Add Model Detail</button>
                            </div> 
                        </div>
                    </div>
                    <div class="card-body border">
                                    <div id="show-body">
                                       
                                        <input type="hidden" id="hidIdDetail" name="BORDE_ID" data-value-type="number"/>
                                        {{-- <input type="hidden" id="drpModelIdDetails" name="MOD_ID" class="form-control"/> --}}
                                        <table class="table table-bordered" id="detail_table">
                                            <thead>
                                              <tr>
                                                  <div class="row">
                                                    <th scope="col">#</th>
                                                    <th>Name Model</th> 
                                                    <th>Due date return</th>
                                                    {{-- <th>Date return</th> --}}
                                                    <th>Is renew</th>
                                                    <th>Action</th>
                                                  </div>
                                              </tr>
                                            </thead>
                                            <tbody id="tbody"></tbody>
                                        </table>    
                                    </div>
                                </div>
                    </div>       
                     </form>
                    </div>                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btnSaveModal" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal borrow detail  --}}
    {{-- <div class="modal fade" id="editModalDetail" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-info"></i> <span id="modalAction"></span> Details Borrow</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frmDetail" enctype="multipart/form-data">
                        <input type="hidden" id="hidIdDetail" name="id" data-value-type="number"/>
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
                    <button type="button" id="btnSaveModalDetail" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('js')
<script>
    var counter=1;
    $(document).ready(function(){
        var tbl= $('#tbl').DataTable({
            columnDefs: [{ orderable: false, targets: [0, 1, 3] }],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '---']
            ],
            iDisplayLength: 50,
            order: [[2, 'asc']],
            aaData: null,
            rowId: 'BOR_ID',
            columns: [
                { data: null, className: 'text-center'},
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<span>' + data.LastName + ' ' + data.FirstName + '</span>';
                    }
                },
                { data: 'Date' },
                { data: null,  render: function ( data, type, row ) {
                    return '<i data-group="grpDetail" class="fas fa-table text-success pointer mr-1"></i>'+
                    '<i data-group="grpEdit" class="fas fa-edit text-info pointer mr-1"></i>' +
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
            AjaxGet(api_url + '/borrows/get', function (result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }

        // bindEnvent
        function bindTableEvents(){
            var rowId = 0;

            $('i[data-group=grpDetail]').on("click").click(function () {
                    var id = $(this).closest('tr').attr('id');
                    var tr = $(this).closest('tr');
                    var row = tbl.row( tr );
                    if ( row.child.isShown() ) {
                        // This row is already open - close it
                        destroyChild(row);
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        createChild(row , id);
                        tr.addClass('shown');
                    }
                });

            $('i[data-group=grpEdit]').off("click").click(function () {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/borrows/get/' + rowId, function (result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function (e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function (yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/borrows/delete/' + rowId, null, function (result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Borrow' + ' "' + result.data.Date + '" has been deleted!';
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

        // borrow return datail
        function destroyChild(row) {
            var table = $("table", row.child());
            table.detach();
            table.DataTable().destroy();

            // And then hide the row
            row.child.hide();
        }

        function createChild ( row, id ) {
            // This is the table we'll convert into a DataTable
            var table = $('<table class="display" width="100%"/>');

            // Display it the child row
            row.child( table ).show();
            var parentId = id;
            // Initialise as a DataTable
            var usersTable = table.DataTable( {
                ajax: {
                    url: api_url + '/borrowdetailmodels/get-by-id/' +id,
                    type: 'get',
                    data: function ( d ) {
                        d.site = id;
                    }
                },
                columnDefs: [{ orderable: false, targets: [0, 6, 7] }],
                order: [[1, 'asc']],
                rowId: 'BORDE_ID',
                aaData: null,
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: '+ Add Detail',
                        className:"btn btn-primary",
                        action: function ( e, dt, node, config ) {
                            infoData = null;
                            $('#editModalDetail').modal('show');
                        }
                    }
                ],
                columns: [
                    { title: '#', data: 'BORDE_ID', className: 'text-center' },
                    { title: 'Model name', data: 'NameModel' },
                    { title: 'Borrow date', data: 'Date' },
                    //{ title: 'Borrower return', data: 'BORETURN_ID' },
                    { title: 'Due date return', data: 'DueDateReturn' },
                   // { title: 'Date', data: 'DateReturn' },
                    { title: 'Is renew', data: 'IsRenew', render: function (data, type, row) {
                    if(data=="true"){
                      return '<i class="far fa-check-circle text-success"></i>';
                    }
                    else
                      return '';
                    }},
                    { title: 'Action', data: null,  render: function ( data, type, row ) {
                            return '<i data-group="grpEditDetail" class="fas fa-edit text-info pointer mr-1"></i>' +
                                '<i data-group="grpDeleteDetail" class="far fa-trash-alt text-danger pointer"></i>';
                        }}
                ],
                select: true,
                drawCallback: function (settings) {
                    bindTableEventsDetail();
                },
                rowCallback: function( row, data, iDisplayIndex ) {
                    var api = this.api();
                    var info = api.page.info();
                    var index = (info.page * info.length + (iDisplayIndex + 1));
                    $('td:eq(0)', row).html(index);
                }
            });
            
            // loadBorrowerReturns(parentId);
            // function loadBorrowerReturns(id) {
            //     $('#drpBoReturn').val(null).empty().trigger('change');
            //     AjaxGet(api_url + '/borrow_returns/get', function(result) {
            //         var optionData = [{
            //             id: 0,
            //             text: 'Select borrower return',
            //             html: 'Select borrower return'
            //         }];
            //         $.each(result.data, function(i, el) {
            //             optionData.push({
            //                 id: el.BORETURN_ID,
            //                 text: el.Date,
            //                 html: '<option class="">' + el.Date + '</option>'
            //             });
            //         });

            //         $("#drpBoReturn").select2({
            //             dropdownParent: $('#editModalDetail'),
            //             width: '100%',
            //             data: optionData,
            //             escapeMarkup: function(markup) {
            //                 return markup;
            //             },
            //             templateResult: function(data) {
            //                 return data.html;
            //             },
            //             templateSelection: function(data) {
            //                 return data.text;
            //             }
            //         });
            //     });
            // }

            // loadModelName();               // của detail
            // function loadModelName() {
            //     $('#drpModelNameId').val(null).empty().trigger('change');
            //     AjaxGet(api_url + '/models/get', function(result) {
            //         var optionData = [{
            //             id: 0,
            //             text: 'Select name model',
            //             html: 'Select name model'
            //         }];
            //         $.each(result.data, function(i, el) {
            //             optionData.push({
            //                 id: el.MOD_ID,
            //                 text: el.NameModel,
            //                 html: '<option value="'+ el.MOD_ID +'">' + el.NameModel + '</option>'
            //             });
            //         });

            //         $("#drpModelNameId").select2({
            //             dropdownParent: $('#editModal'),
            //             width: '100%',
            //             data: optionData,
            //             escapeMarkup: function(markup) {
            //                 return markup;
            //             },
            //             templateResult: function(data) {
            //                 return data.html;
            //             },
            //             templateSelection: function(data) {
            //                 return data.text;
            //             }
            //         });
            //     });
            // }
        // ----------- end select2 borrow detail-----------------
            $('#editModal').modal({ show: false }).on('show.bs.modal', function (e) {
            // validatorDetail.resetForm();
                // check infoData edit or add new
                if (infoData != null) {
                    $('#modalAction').text('Update');
                    $('#hidIdDetail').val(infoData.BORDE_ID);
                    $('#drpModelIdDetails').val(infoData.MOD_ID).trigger('change');
                    $('#drpBorrowId').val(infoData.BOR_ID).trigger('change');
                    $('#drpBoReturn').val(infoData.BORETURN_ID).trigger('change');
                    $('#txtDueDate').val(infoData.DueDateReturn);
                    $('#txtDateReturn').val(infoData.DateReturn);
                    $('#chkIsRenew').bootstrapSwitch('state', infoData.IsRenew);
                } else {
                    $('#modalAction').text('New');
                    $('#hidIdDetail').val(0);
                    $('#drpModelIdDetails').val('');
                    $('#drpBorrowId').val(parentId);
                    $('#drpBoReturn').val(parentId);
                    $('#txtDueDate').val('');
                    $('#txtDateReturn').val('');
                    $('#chkIsRenew').bootstrapSwitch('state',true);
                }
            }); 
        }

        function bindTableEventsDetail() {
            var rowId = 0;

            $('i[data-group=grpEditDetail]').off("click").click(function () {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/borrowdetailmodels/get/' + rowId, function (result) {
                    infoData = result.data;
                    $('#editModalDetail').modal('show');
                });
            });

            $('i[data-group=grpDeleteDetail]').off('click').click(function (e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function (yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/borrowdetailmodels/delete/' + rowId, null, function (result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Item' + ' "' + result.data.IMEXDID + '" has been deleted!';
                                loadTable();
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

        // Validation borrow detail
        var validatorDetail = $('#frmDetail').validate({
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
            }},
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


        //-----------------datimepicker-------------------
        jQuery.datetimepicker.setDateFormatter('moment')
            $('#txtDate').datetimepicker({
                timepicker: true,
                datepicker: true,
                hours12: false,
                step: 15,
                format: 'YYYY-MM-DD HH:mm',
            })
            $('#toggle').on('click', function () {
                $('#txtDate').datetimepicker('toggle')
            })
        //-----------------end datimepicker-------------------

        

        // ----------- select2 -----------------
        loadBorrower();
        function loadBorrower() {
            $('#drpBorrowerId').val(null).empty().trigger('change');
            AjaxGet(api_url + '/borrowers/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: 'Select borrower',
                    html: 'Select borrower'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.BORROWER_ID,
                        text: el.LastName + ' ' + el.FirstName,
                        html: '<option class="">' + el.LastName  + ' ' + el.FirstName + '</option>'
                    });
                });

                $("#drpBorrowerId").select2({
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


        // ----------- select2 borrow detail-----------------
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

        loadBorrows();
        function loadBorrows() {
            $('#drpBorrowId').val(null).empty().trigger('change');
            AjaxGet(api_url + '/borrows/get', function(result) {
                var optionData = [{
                    id: 0,
                    text: 'Select borrow date',
                    html: 'Select borrow date'
                }];
                $.each(result.data, function(i, el) {
                    optionData.push({
                        id: el.BOR_ID,
                        text: el.Date,
                        html: '<option class="">' + el.Date + '</option>'
                    });
                });

                $("#drpBorrowId").select2({
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

        //-----------------datimepicker detail-------------------
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
        //-----------------end datimepicker detail-------------------

        


        
        $('#btnAddModelDetail').on('click',function () {
           
            $('#show-body').fadeIn();
                var tr = '<tr>'
                        +'<td>'+ (counter++) +'</td>'
                        +'<td>'+
                            '<select id="drpModelIdDetails" name="MOD_ID" class="form-control"></select>'+
                        '</td>'
                        +
                        '<td>'+
                            '<input type="text" class="form-control" id="txtDueDate" name="DueDateReturn">'
                        +'</td>'+
                        '<td>'+
                            '<input type="checkbox" id="chkIsrenew" name="IsRenew" data-off-color="danger" data-on-color="success" value="true">'
                        +'</td>'+
                        '<td><button type="button" id="btnRemove" class="btn btn-danger">Remove</button></td>'+
                    '</tr>';
                $('#tbody').append(tr);
                $('#tbody').on('click','#btnRemove', function (){
                    $(this).closest('tr').remove();
                    
                }); 


               loadModelsDetails();
        function loadModelsDetails() {
            $('#drpModelIdDetails').val(null).empty().trigger('change');
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
                        html: '<option class="" >' + el.NameModel + '</option>'
                    });
                });
                
                $("#drpModelIdDetails").select2({
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
        });
        

        // Add button
        $('#btnAdd').click(function(){
                    infoData=null;
                    counter=1;
                    $('#tbody > tr').remove();
                $('#show-body').hide();
                    $('#editModal').modal('show');
                    loadBorrower();
                    loadModels();
                });
                var validator = $('#frm').validate({
                    rules: {
                        BORROWER_ID  :{
                                required: true,
                                min: 1
                            },
                        Date:{
                            required: true,
                        // digits: true
                        }
                    },
                    messages: {
                        BORROWER_ID  : {
                                min: "Please select id borrower"
                            },
                        Date:{
                            required: 'Please enter date.',
                            //digits: 'Date is invalid!.'
                        }
                    }
                });
        // Edit 
        $('#editModal').modal({ show: false }).on('show.bs.modal', function (e) {
                   validator.resetForm();
                    // check infoData edit or add new
                    if (infoData != null) {
                        $('#modalAction').text('Update');
                        
                        $('#hidId').val(infoData.BOR_ID);
                        $('#drpBorrowerId').val(infoData.BORROWER_ID).trigger('change');
                        $('#txtDate').val(infoData.Date);
                        $("#drpModelIdDetails").val(infoData.MOD_ID);
                    } else {
                        
                        $('#modalAction').text('New');
                        $('#hidId').val('0');
                        $('#drpBorrowerId').val('0').trigger('change');
                        $('#txtDate').val('');
                        $("#drpModelIdDetails").val('');
                    }
                });

        // Save button
        $('#btnSaveModal').click(function(){
                    if($('#frm').valid()){
                        //save data
                        data = $('#frm').serializeJSON();
                        id = $('#hidId').val();
                        if(id > 0){    //update date
                            AjaxPost(api_url + '/borrows/update', data, function(res) {
                                if (res.error == 0) {
                                    PNotify.success({title: 'Info', text: 'Borrow has been updated date successfully.'});
                                    $('#editModal').modal('hide');
                                    loadTable();
                                    loadModelsDetails();
                                } else {
                                    PNotify.alert({title: 'Warning', text: res.message});
                                }
                            });
                         }else{ //add new data
                             AjaxPost(api_url + '/borrows/add', data, function(res) {                    
                                if (res.error == 0) {
                                    PNotify.success({title: 'Info', text: 'Borrow has been added date successfully.'});
                                    $('#editModal').modal('hide');
                                    loadTable();
                                    loadModelsDetails();
                                } else {
                                    PNotify.alert({title: 'Warning', text: res.message});
                                }
                            });
                }
            }
        });

       

        //Add Detail
        $('#btnAddDetail').click(function(){
                    infoData=null;
                    $('#editModalDetail').modal('show');
                    loadModels();
                    loadBorrows();
                    // loadBorrowerReturns();
                });

        //Edit Detail
       
        //Save Detail
        $('#btnSaveModalDetail').click(function(){
            if ($('#frm').valid()) {
                // save data
                data = $('#frmDetail').serializeJSON();
                id = $('#hidIdDetail').val();
                if (id > 0) { // update
                    AjaxPost(api_url + '/borrowdetailmodels/update', data, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Detail borrow has been updated successfully.'});
                            $('#editModalDetail').modal('hide');
                            loadTable();
                            //loadModels();
                            loadModelsDetails();
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
                            $('#editModalDetail').modal('hide');
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