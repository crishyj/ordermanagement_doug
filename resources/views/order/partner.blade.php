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
                        </div>
                    </div>                   

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush text-center"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Product Name') }}</th>
                                    <th scope="col">{{ __('Product Image') }}</th>
                                    <th scope="col">{{ __('Order Status')}}</th>  
                                    <th scope="col">{{ __('Tracking Information')}}</th>  
                                    <th scope="col"></th>                                  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $option)
                                    <tr>         
                                        <input type="hidden" name="id" class="id" value="{{$option->id}}" />  
                                        <input type="hidden" name="name" class="name" value="{{$option->name}}" />  
                                        <input type="hidden" name="image" class="image" value="{{$option->image}}" />  
                                        <input type="hidden" name="info" class="info" value="{{$option->info}}" />  
                                        <input type="hidden" name="track" class="track" value="{{$option->track}}" />  

                                        <td>{{ $option->name }}</td>
                                        <td> <img src = {{asset($option->image)}} width = 100px> </td> 
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
                                        <td>{{ $option->track }}</td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-primary track_btn" data-id="{{$option->id}}" data-toggle="tooltip" data-placement="bottom" title="" data-modal="trackModal"> View Order </a>
                                            <!-- <div class="dropdown">

                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a href="#" class="dropdown-item detail_btn" data-id="{{$option->id}}" data-toggle="tooltip" data-placement="bottom" title="" data-modal="trackModal"> <i class="ni ni-tag"></i> View Order </a>
                                                    <a href="#" class="dropdown-item stat_btn" data-id="{{$option->id}}" data-toggle="tooltip" data-placement="bottom" title="" data-modal="assignModal"> <i class="ni ni-tag"></i> View Status </a>
                                                    <a href="#" class="dropdown-item track_btn" data-id="{{$option->id}}" data-toggle="tooltip" data-placement="bottom" title="" data-modal="trackModal"> <i class="ni ni-tag"></i> Add Tracking </a>
                                                </div>
                                            </div> -->
                                         
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


    <div class="modal fade" id="trackModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="post" id="track_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Track Order')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id1" class="order_id1" id ="order_id1" />
                        <div class="form-group">
                            <label for="name" class="font-weight-600"> {{ __('Product Name')}} :</label>
                            <input type="text" name="name" id="name" class="form-control name" required>
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
                                <textarea class="form-control info" placeholder="{{ __('Shipping Information') }}" name="info" id="info" cols="30" rows="10" required></textarea>
                            </div>                           
                        </div>   

                        <div class="form-group">
                            <label for="users"> Status </label>
                            <select class="form-control" id="stat" name="stat">
                                @foreach ($stats as $stat)
                                    <option  value="{{$stat->id}}">{{ $stat->name }}</option>
                                @endforeach
                            </select>
                        </div> 

                        <div class="form-group">
                            <label for="track" class="font-weight-600"> {{ __('Track Information')}} :</label>
                            <input type="text" name="track" id="track" class="form-control track" required>
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
            margin-top: 25%;
        }

        #imageModal img{
            height: -webkit-fill-available;
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
           
            $(document).on('click', '.track_btn', function (){
                let id = $(this).data('id');
                let name = $(this).parents('tr').find('.name').val().trim();     
                let product_image = $(this).parents('tr').find('.image').val().trim();
                let info = $(this).parents('tr').find('.info').val().trim(); 
                let track = $(this).parents('tr').find('.track').val().trim(); 

                $("#track_form .order_id1").val(id);
                $("#track_form .name").val(name);
                $("#track_form .product_image").attr("src", '../'+product_image);
                $("#track_form .info").val(info);
                $("#track_form .track").val(track);
                $("#trackModal").modal();
            });


            $("#track_form .btn-submit").click(function(){
                let _token = $('input[name=_token]').val();
                let id = $('#order_id1').val();   
                let name = $('#name').val();
                let info = $('#info').val();
                let stat = $('#stat').val(); 
                let track = $('#track').val();

                var form_data =new FormData();
                form_data.append("_token", _token);
                form_data.append("id", id);
                form_data.append("name", name);   
                form_data.append("info", info);   
                form_data.append("stat", stat);       
                form_data.append("track", track);            
                
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
