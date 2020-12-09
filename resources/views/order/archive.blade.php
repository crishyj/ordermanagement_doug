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
                                <h3 class="mb-0">{{ __('Archive Orders') }}</h3>
                            </div>                            
                        </div>
                    </div>                   

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush text-center"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="dol">{{ __('Created')}}</th>
                                    <th scope="col">{{ __('Product Name') }}</th>
                                    <th scope="col">{{ __('Product Image') }}</th>
                                    <th scope="col">{{ __('Order information') }}</th>
                                    <th scope="col">{{ __('Partner Name') }}</th>     
                                    <th scope="col">{{ __('Order Status')}}</th>  
                                    <th scope="col">{{ __('Order Track')}}</th>  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($options as $option)
                                    <tr>         
                                        <input type="hidden" name="id" class="id" value="{{$option->id}}" />  
                                        <input type="hidden" name="name" class="name" value="{{$option->name}}" />  
                                        <input type="hidden" name="image" class="image" value="{{$option->image}}" />  
                                        <input type="hidden" name="info" class="info" value="{{$option->info}}" />  

                                        <td> {{$option->created_at}} </td>
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
                                        <td>
                                            {{ $option->track }}
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
@endpush
