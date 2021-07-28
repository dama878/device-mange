@extends('admin.layouts.app')
@section('title', 'Device')
@section('pageTitle', 'Device')
@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <div class="card-tools d-flex">
                <div class="mr-3">
                    <select data-column="0" class="form-control filter-select">
                        <option value="">select name type</option>
                        @foreach($typeNames as $typeName)
                            <option value="{{ $typeName }}">{{$typeName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mr-3">
                    <select data-column="0" class="form-control filter-select">
                        <option value="">select name manufacturer</option>
                        @foreach($manNames as $manName)
                            <option value="{{ $manName }}">{{$manName}}</option>
                        @endforeach
                    </select>
                </div>
                <button id="btnAdd" type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </div>
        </div>
        <div class="card-body">
            <table id="tbl" class="table table-bordered table-hover table-striped">
                <thead>
                    <th style="width: 40px;">#</th>
                    <th>Name</th>
                    <th>TypeName</th>
                    <th>Manufacturer</th>
                    <th>Status</th>
                    
                    <th>Image</th>
                    <th>Display Order</th>
                    <th style="width: 60px"></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@section('footer')
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="modalName" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalName"><i class="fa fa-info"></i> <span id="modalAction"></span> Device</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-c-tabs">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs md-tabs tabs-2 light-blue darken-3" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#panel7" role="tab"><i class="fas fa-user mr-1"></i>
                      infomation</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#panel8" role="tab"><i class="fas fa-edit"></i>
                      description</a>
                  </li>
                </ul>
        
                <!-- Tab panels -->
                <div class="tab-content">
                  <!--Panel 7-->
                  <div class="tab-pane fade in show active" id="panel7" role="tabpanel">
        
                    <!--Body-->
                    <div class="modal-body mb-1">
                       <form method="post" id="frm" enctype="multipart/form-data">
                            <input type="hidden" id="hidId" name="id" data-value-type="number"/>
                            <div class=" form-row">
                                <label for="txtName" class="col-sm-3 col-form-label">Device Name</label>
                                <div class="form-group col-sm">
                                    <input type="text" class=" form-control" id="txtName" name="DevName" maxlength="200" placeholder="Device Name" autocomplete="off">
                                </div>
                            </div>
                            {{-- <div class="form-group form-row">
                                <label for="txtTYPE_ID" class="col-sm-3 col-form-label">Device TYPE</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="txtTYPE_ID" name="TYPE_ID" maxlength="200" placeholder="Device Name" autocomplete="off">
                                </div>
                            </div> --}}
                            <div class=" form-row">
                                <label for="drpTYPE_ID" class="col-sm-3 col-form-label">Device TYPE</label>
                                <div class="form-group col-sm">
                                    <select id="drpTYPE_ID" name="TYPE_ID"></select>
                                </div>
                            </div>
                            <div class=" form-row">
                                <label for="drpMAN_ID" class="col-sm-3 col-form-label">Device Manufacturer</label>
                                <div class="form-group col-sm">
                                    <select id="drpMAN_ID" name="MAN_ID"></select>
                                </div>
                            </div>
                           
                            <div class="form-group form-row">
                                <label for="txtKeyWord" class="col-sm-3 col-form-label">Device KeyWord</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="txtKeyWord" name="KeyWord" maxlength="200" placeholder="Device KeyWord" autocomplete="off">
                                    
                                </div>
                            </div>
                            <div class=" form-row">
                                <label for="txtStatus" class="col-sm-3 col-form-label">Device Status</label>
                                <div class="col-sm form-group">
                                    <input type="number" min="1" max="9" onkeydown="if(parseInt(this.value)>9){ this.value =9; return false; }" class="form-control" id="txtStatus" name="Status" maxlength="200" placeholder="Device Status" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="txtDetail" class="col-sm-3 col-form-label">Device Serial Number</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="txtSerialNumber" name="SerialNumber" maxlength="200" placeholder="Serial Number" autocomplete="off">
                                </div>
                            </div>
                            
                            {{-- <div class="form-group form-row">
                                <label for="txtGuaranteeStart" class="col-sm-3 col-form-label">Device Guarantee Start</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="picker" name="GuaranteeDate" maxlength="200" placeholder="Guarantee Start" autocomplete="off">
                                </div>
                            </div> --}}
                            {{-- <div class="form-group form-row">
                                <label for="txtGuaranteeStart" class="col-sm-3 col-form-label text-right">Device Guarantee Start: </label>
                                <div class="col-sm input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" id="toggle" class="input-group-text">
                                            <i class="fa fa-calendar-alt"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" id="txtGuaranteeStart" name="GuaranteeStart" >
                                </div>
                            </div> --}}
                            <div class="form-group form-row">
                                <label for="txtGuaranteeStart" class="col-sm-3 col-form-label">Device Guarantee Start</label>
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm-6 input-group">
                                            <input type="text" class="form-control" id="txtGuaranteeStart" name="GuaranteeStart" placeholder="Guarantee Start">
                                            <div class="input-group-prepend">
                                                <button type="button" id="toggle" class="input-group-text">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </button>
                                            </div>
                                            
                                        </div>
                                        <div class="col-sm-6 d-flex input-group">
                                            <input type="text" class="form-control" id="txtGuaranteeEnd" name="GuaranteeEnd" placeholder="Guarantee End">   
                                            <div class="input-group-prepend">
                                                <button type="button" id="toggle1" class="input-group-text">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </button>
                                            </div>
                                            
                                        </div>
                                    </div>      
                                </div>
                            </div>
                                               
                  
                            <div class="form-group form-row">
                                <label for="txtFile" class="col-sm-3 col-form-label">Image (1920x1080)</label>
                                <div class="col-sm">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="txtFile" name="Img" autocomplete="off">
                                        <label class="custom-file-label" id="txtFileLabel" for="txtFile" data-browse="Browse">Upload image</label>
                                    </div>
                                    <img  id="imgPreview" src="" class="mt-2 img-thumbnail"/>
                                </div>
                            </div>
                            <div class=" form-row">
                                <label for="txtDisplayOrder" class="col-sm-3 col-form-label">Display order</label>
                                <div class="col-sm form-group">
                                    <input type="number" class="form-control" id="txtDisplayOrder" name="DisplayOrder" maxlength="8" placeholder="Display order" data-value-type="number" style="width: 80px"/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="chkIsPublished" class="col-sm-3 col-form-label">Is Published</label>
                                <div class="col-sm">
                                    <input type="checkbox" id="chkIsPublished" name="IsPublished" data-off-color="danger" data-on-color="primary" value="true">
                                </div>
                            </div>
                       
                    </div>
                    <!--Footer-->
                   
        
                  </div>
                  <!--/.Panel 7-->
        
                  <!--Panel 8-->
                  <div class="tab-pane fade" id="panel8" role="tabpanel">
        
                    <!--Body-->
                    <div class="modal-body">
                        <div class="form-group form-row">
                            <label for="txtDescription" class="col-sm-3 col-form-label">Device Description</label>
                            <div class="col-sm">
                                <textarea rows="5" type="text" class="form-control" id="txtDescription" name="Description" maxlength="200" placeholder="Device Description" autocomplete="off"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group form-row">
                            <label for="txtDetail" class="col-sm-3 col-form-label">Device Detail</label>
                            <div class="col-sm">
                                <textarea rows="5" type="text" class="form-control" id="txtDetail" name="Detail" maxlength="200" placeholder="Device Detail" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                  
                  <!--/.Panel 8--> </form>
                </div>
        
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
            columnDefs: [{ orderable: false, targets: [0, 2,3,4,5,6,7] }],
            // columnDefs: [{ orderable: false, targets: [0, 2, 5,6,7] }],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '---']
            ],
            iDisplayLength: 50,
            order: [[1, 'asc']],
            aaData: null,
            rowId: 'DEV_ID',
            columns: [
                { data: null, className: 'text-center' },
                { data: 'DevName', className: 'text-center' },
                { data: 'TypeName',className: 'text-center'  },
                { data: 'ManName',className: 'text-center'  },
                { data: 'Status', className: 'text-center' },
                
                { data: null,  className: 'text-center',render: function ( data, type, row ) {
                    return '<img style=" margin-left: auto; margin-right: auto;" width="100" height="50" class="img-fluid" src="' + base_url + '/public/data/devices/' + data.Img + '"/>';
                }},
                { data: 'DisplayOrder',className: 'text-center' },
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
            AjaxGet(api_url + '/devices/get', function (result) {
                tbl.clear().draw();
                tbl.rows.add(result.data); // Add new data
                tbl.columns.adjust().draw(); // Redraw the DataTable
            });
        }
        function bindTableEvents() {
            var rowId = 0;

            $('i[data-group=grpEdit]').off("click").click(function () {
                rowId = $(this).closest('tr').attr('id');
                AjaxGet(api_url + '/devices/get/' + rowId, function (result) {
                    infoData = result.data;
                    $('#editModal').modal('show');
                });
            });

            $('i[data-group=grpDelete]').off('click').click(function (e) {
                rowId = $(this).closest('tr').attr('id');
                $('#' + rowId).addClass('table-danger');
                ShowConfirm('Confirmation', 'Are you sure you want to delete selected row?', 'Yes', 'No', function (yesClicked) {
                    if (yesClicked) {
                        AjaxPost(api_url + '/devices/delete/' + rowId, null, function (result) {
                            if (result.error == 0) {
                                tbl.row('#' + rowId).remove().draw();
                                var content = 'Device' + ' "' + result.data.Name + '" has been deleted!';
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

        $("#txtFile").change(function(e){
            ReadImageUrl(this, "imgPreview", "txtFileLabel");
        });
        //----------------- filter--------------//

        $('.filter-select').change(function () {
                    tbl.search($(this).val()).draw();
                })

        //-------------- end :filter----------------//


        //---------------date range --------------//
        
                    $('#txtGuaranteeStart').datetimepicker({
                        timepicker: false,
                        datepicker: true,
                        hours12: false,
                        
                        format: 'Y-m-d',

                        
                    })
                    $('#toggle').on('click', function () {
                        $('#txtGuaranteeStart').datetimepicker('toggle')
                     })
                   
                    $('#txtGuaranteeEnd').datetimepicker({
                        timepicker: false,
                        datepicker: true,
                        hours12: false,
                        
                        format: 'Y-m-d',
                        
                    })
                    $('#toggle1').on('click', function () {
                        $('#txtGuaranteeEnd').datetimepicker('toggle')
                     })



   


//---------------end :date range ----------------//



// ----------- select2 -----------------
    // loadTypes();
    //     function loadTypes() {
    //         $('#drpTYPE_ID').val(null).empty().trigger('change');
    //         AjaxGet(api_url+'/types/get', function(result) {
    //             var optionData = [{ id: 0,text: '------', html: '----'}];
    //             $.each(result.data, function (i, el) {
                    
    //                 optionData.push({ id: el.TYPE_ID, text: el.TypeName, html: '<option class="">' + el.TypeName + '</option>' });
    //             });

    //             $("#drpTYPE_ID").select2({
    //                 dropdownParent: $('#editModal'),
    //                 width: '100%',
    //                 data: optionData,
    //                 escapeMarkup: function (markup) {
    //                     return markup;
    //                 },
    //                 templateResult: function (data) {
    //                     return data.html;
    //                 },
    //                 templateSelection: function (data) {
    //                     return data.text;
    //                 }
    //             });
    //         });
    //     }

        loadTypes();
        function loadTypes() {
            $('#drpTYPE_ID').val(null).empty().trigger('change');
            AjaxGet(api_url+'/types/get', function(result) {
                var optionData = [{ id: 0,text: '------', html: '----' }];
                $.each(result.data, function (i, el) {
                    depth = el.Depth > 0 ? el.Depth + 2 : 0;
                    optionData.push({ id: el.TYPE_ID, text: el.TypeName, html: '<option class="ml-' + depth + '">' + el.TypeName + '</option>' });
                });

                $("#drpTYPE_ID").select2({
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



        loadManufacturers();
        function loadManufacturers() {
            $('#drpMAN_ID').val(null).empty().trigger('change');
            AjaxGet(api_url+'/manufacturers/get', function(result) {
                var optionData = [{ id: 0,text: '------', html: '----'}];
                $.each(result.data, function (i, el) {
                    
                    optionData.push({ id: el.MAN_ID, text: el.ManName, html: '<option class="">' + el.ManName + '</option>' });
                });

                $("#drpMAN_ID").select2({
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
//         ----------- end: select2 ------------

        $('#btnAdd').click(function(){
            infoData = null;
            $('#editModal').modal('show');
            
        });

        $("#txtFile").change(function(e){
                ReadImageUrl(this, "imgPreview", "txtFileLabel");
            });

        var validator = $('#frm').validate({
            rules: {
                DevName: {
                    required:true,
                    
                },
                TYPE_ID : {
                    required: true,

                },
                MAN_ID :{
                    required:true,
                },
                DisplayOrder: {
                    required: true,
                    digits: true
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
                Name: 'Please enter name.',
                DisplayOrder: {
                    required: 'Please enter number.',
                    digits: 'Number is invalid!.'
                },
                TYPE_ID: 'Please enter Type.',
                MAN_ID: 'Please enter Device.',
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
            },
        });
    

        $('#editModal').modal({ show: false }).on('show.bs.modal', function (e) {
            validator.resetForm();
            // check infoData edit or add new
            if (infoData != null) {
                $('#modalAction').text('Update');

                $('#hidId').val(infoData.DEV_ID);
                $('#txtName').val(infoData.DevName);
                $('#drpTYPE_ID').val(infoData.TYPE_ID).trigger('change');
                $('#drpMAN_ID').val(infoData.MAN_ID).trigger('change');
                $('#txtDescription').val(infoData.Description);
                $('#txtKeyWord').val(infoData.KeyWord);
                $('#txtStatus').val(infoData.Status);
                $('#txtSerialNumber').val(infoData.SerialNumber);
                $('#txtDetail').val(infoData.Detail);
                $('#txtGuaranteeStart').val(infoData.GuaranteeStart);
                $('#txtGuaranteeEnd').val(infoData.GuaranteeEnd);
                $('#txtDisplayOrder').val(infoData.DisplayOrder);
                $('#chkIsPublished').bootstrapSwitch('state', infoData.IsPublished);

                $("#txtFileLabel").text(infoData.Img);
                if (infoData.Img != null)
                    $("#imgPreview").attr('src',base_url +'/public/data/devices/' + infoData.Img);
                else {
                    $("#txtFileLabel").text('Upload image');
                    $("#imgPreview").attr('src','');
                }
                
            } else {
                $('#modalAction').text('New');
                $('#hidId').val('0');
                $('#txtName').val('');
                $('#drpTYPE_ID').val('');
                $('#drpMAN_ID').val('');
                $('#txtDescription').val('');
                $('#txtKeyWord').val('');
                $('#txtStatus').val('');
                $('#txtSerialNumber').val('');
                $('#txtDetail').val('');
                $('#txtGuaranteeStart').val('');
                $('#txtGuaranteeEnd').val('');
                
                $('#txtDisplayOrder').val('1');
                $('#chkIsPublished').bootstrapSwitch('state', true);

                $("#txtFileLabel").text('Upload image');
                $("#imgPreview").attr('src','');
            }
        }).on('shown.bs.modal', function () {
            $('#txtName').focus();
        });

        $('#btnSaveModal').click(function(){
            if ($('#frm').valid()) {
                // save data
                var form = $("#frm")[0]; // high importance!, here you need change "yourformname" with the name of your form
                var formdata = new FormData(form); // high importance! 
                data = $('#frm').serializeJSON();
                id = $('#hidId').val();
                if (id > 0) { // update
                    AjaxPostForm(api_url + '/devices/update', formdata, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Device has been updated successfully.'});
                            $('#editModal').modal('hide');
                            loadTable();
                        } else {
                            PNotify.alert({title: 'Warning', text: res.message});
                        }
                    });
                } else { // add
                    console.log(data);
                    AjaxPostForm(api_url + '/devices/add', formdata, function(res) {
                        if (res.error == 0) {
                            PNotify.success({title: 'Info', text: 'Device has been added successfully.'});
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
 