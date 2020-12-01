@extends('layouts.app', [
    'parentSection' => 'dashboards',
    'elementName' => 'dashboard'
])

@section('content')    
    <div class="container mt-8 pb-5">
    <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Orders') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('order.register') }}" class="btn btn-sm btn-primary">{{ __('Create Order') }}</a>
                            </div>
                        </div>
                    </div>                   

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush text-center"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Product Name') }}</th>
                                    <th scope="col">{{ __('Product Image') }}</th>
                                    <th scope="col">{{ __('Shipping information') }}</th>
                                    <th scope="col">{{ __('Partner Name') }}</th>     
                                    <th scope="col">{{ __('Order Status')}}</th>  
                                    <th scope="col"></th>                                  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($options as $option)
                                    <tr>         
                                        <input type="hidden" name="id" class="id" value="{{$option->id}}" />  
                                        <input type="hidden" name="name" class="name" value="{{$option->name}}" />  
                                        <input type="hidden" name="image" class="image" value="{{$option->image}}" />  
                                        <input type="hidden" name="info" class="info" value="{{$option->info}}" />  

                                        <td>{{ $option->name }}</td>
                                        <td> <img src = {{asset($option->image)}} width = 100px> </td> 
                                        <td>{{ $option->info }}</td>
                                        <td> 
                                            @forelse($users as $user)
                                                @php
                                                    if($user->id == $option->users_id){
                                                        echo $user->name;
                                                    } 
                                                @endphp
                                            @empty       
                                            
                                            @endforelse   
                                        </td>
                                        <td>
                                            @forelse($stats as $stat)
                                                @php
                                                    if($stat->id == $option->stats_id){
                                                        echo $stat->name;
                                                    } 
                                                @endphp
                                            @empty       
                                            
                                            @endforelse  
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a href="#" class="dropdown-item modal-btn2" data-id="{{$option->id}}" data-toggle="tooltip" data-placement="bottom" title="" data-modal="modal-1"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                                                    @if($option->users_id == '')
                                                        <a href="#" class="dropdown-item assign_btn" data-id="{{$option->id}}" data-toggle="tooltip" data-placement="bottom" title="" data-modal="assignModal"> <i class="ni ni-tag"></i> Assign </a>
                                                    @endif
                                                    <a href="{{route('order.delete', $option->id)}}" onclick="return window.confirm('Are you sure?')" class="dropdown-item" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Delete"><i class="fa fa-trash"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal fade" id ="imageModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <img src = "" class="product_image">
            </div>
        </div>
    </div> -->
    
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="post" id="edit_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Edit Order')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="id" id ="id" />
                        <div class="form-group">
                            <label for="name" class="font-weight-600"> {{ __('Product Name')}} :</label>
                            <input type="text" name="name" id="name" class="form-control name" required>
                        </div>   
                        <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                            <label for="image">Product Image</label>
                            <div>
                                <img src = "" class="product_image">
                            </div>
                            <div class="input-group input-group-alternative mb-3">
                                <input class="form-control image" type="file" name="image" id ="image" required>
                            </div>                            
                        </div>

                        <div class="form-group{{ $errors->has('info') ? ' has-danger' : '' }}">
                            <label for="info">Shipping Information</label>
                            <div class="input-group input-group-alternative">                                   
                                <textarea class="form-control info" placeholder="{{ __('Shipping Information') }}" name="info" id="info" cols="30" rows="10" required></textarea>
                            </div>                           
                        </div>                            
                    </div>              
                    
                    <div class="modal-footer">    
                        <button type="button" class="btn btn-primary btn-submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>&nbsp;{{ __('Save')}}</button>                       
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>&nbsp;{{ __('Close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="post" id="assign_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Assign Order')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" class="order_id" id ="order_id" />
                        <div class="form-group">
                            <label for="name" class="font-weight-600"> {{ __('Product Name')}} :</label>
                            <input type="text" name="name" id="name" class="form-control name" readonly>
                        </div>   
                        <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                            <label for="image">Product Image</label>
                            <div>
                                <img src = "" class="product_image">
                            </div>                                                     
                        </div>

                        <div class="form-group{{ $errors->has('info') ? ' has-danger' : '' }}">
                            <label for="info">Shipping Information</label>
                            <div class="input-group input-group-alternative">                                   
                                <textarea class="form-control info" placeholder="{{ __('Shipping Information') }}" name="info" id="info" cols="30" rows="10" readonly></textarea>
                            </div>                           
                        </div>   

                        <div class="form-group">
                            <label for="users"> Partner Name </label>
                            <select class="form-control" id="user" name="user">
                                @foreach ($users as $user)
                                    <option  value="{{$user->id}}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>                         
                    </div>              
                    
                    <div class="modal-footer">    
                        <button type="button" class="btn btn-primary btn-submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>&nbsp;{{ __('Save')}}</button>                       
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>&nbsp;{{ __('Close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <style>
        #imageModal{
            margin-top: 5%;
        }

        #imageModal img{
            height: -webkit-fill-available;
        }

        #imageModal .modal-dialog .modal-content img{
            width: 100%;
        } 

        .modal-body img{
            width: 100%;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script>
        $(document).ready(function(){

            $(document).on('click', '.image_modal', function (){
                let product_image = $(this).parents('tr').find('.image').val().trim();
                $("#imageModal .product_image").attr("src", '../'+product_image);
                $("#imageModal").modal();
            });


            $(document).on('click', '.modal-btn2', function (){
                let id = $(this).data('id');
                let name = $(this).parents('tr').find('.name').val().trim();     
                let product_image = $(this).parents('tr').find('.image').val().trim();
                let info = $(this).parents('tr').find('.info').val().trim(); 
                

                $("#edit_form .id").val(id);
                $("#edit_form .name").val(name);
                $("#edit_form .product_image").attr("src", '../'+product_image);
                $("#edit_form .info").val(info);
                $("#editModal").modal();
            });

            $("#edit_form .btn-submit").click(function(){
                let _token = $('input[name=_token]').val();
                let id = $('#id').val();
                let name = $('#name').val();
                let info = $('#info').val(); 
                var image = $('#image').prop('files')[0];
             

                var form_data =new FormData();
            
                form_data.append("_token", _token);
                form_data.append("id", id);
                form_data.append("name", name);
                form_data.append("info", info);
                form_data.append("image", image);
                form_data.append("upload_file", true);
                $.ajax({
                    url: "{{route('order.update')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(response) {
                        if(response == 'success') {  
                            window.location.reload();                          
                        } else {
                            let messages = response.data;
                            if(messages.option) {                               
                            }
                        }
                    },
                    error: function(response) {
                        $("#ajax-loading").fadeOut();
                        if(response.responseJSON.message == 'The given data was invalid.'){                            
                            let messages = response.responseJSON.errors;
                            if(messages.option) {                                
                            }
                            alert("Something went wrong");
                            window.location.reload();        
                        } else {
                            alert("Something went wrong");
                        }
                    }
                });
            });

            $(document).on('click', '.assign_btn', function (){
                let id = $(this).data('id');
                let name = $(this).parents('tr').find('.name').val().trim();     
                let product_image = $(this).parents('tr').find('.image').val().trim();
                let info = $(this).parents('tr').find('.info').val().trim(); 

                $("#assign_form .order_id").val(id);
                $("#assign_form .name").val(name);
                $("#assign_form .product_image").attr("src", '../'+product_image);
                $("#assign_form .info").val(info);
                $("#assignModal").modal();
            });


            $("#assign_form .btn-submit").click(function(){
                let _token = $('input[name=_token]').val();
                let id = $('#order_id').val();         
                let user = $('#user').val();

                var form_data =new FormData();
                form_data.append("_token", _token);
                form_data.append("id", id);
                form_data.append("user", user);            
                
                $.ajax({
                    url: "{{route('order.update')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(response) {
                        if(response == 'success') {  
                            window.location.reload();                          
                        } else {
                            let messages = response.data;
                            if(messages.option) {                               
                            }
                        }
                    },
                    error: function(response) {
                        $("#ajax-loading").fadeOut();
                        if(response.responseJSON.message == 'The given data was invalid.'){                            
                            let messages = response.responseJSON.errors;
                            if(messages.option) {                                
                            }
                            alert("Something went wrong");
                            window.location.reload();        
                        } else {
                            alert("Something went wrong");
                        }
                    }
                });
            });
        });
    </script>
@endpush
