@extends('layouts.app', [
    'parentSection' => 'dashboards',
    'elementName' => 'dashboard'
])

@section('content')    
    <div class="container mt-8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8">
                <div class="card bg-secondary shadow border-0">
                   
                    <div class="card-body px-lg-5 py-lg-5">
                       
                        <form role="form" method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label for="name">Product Name</label>
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Product Name') }}" type="text" name="name" value="{{ old('name') }}" required autofocus>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                                <label for="image">Product Image</label>
                                <div class="input-group input-group-alternative mb-3">
                                    <input class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" type="file" name="image" value="{{ old('image') }}" required>
                                </div>
                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                           
                            <div class="form-group{{ $errors->has('info') ? ' has-danger' : '' }}">
                                <label for="info">shipping Information</label>
                                <div class="input-group input-group-alternative">                                   
                                    <textarea class="form-control{{ $errors->has('info') ? ' is-invalid' : '' }}" placeholder="{{ __('Shipping Information') }}" name="info" id="info" cols="30" rows="10" required></textarea>
                                </div>
                                @if ($errors->has('info'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('info') }}</strong>
                                    </span>
                                @endif
                            </div>                          
                           

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">{{ __('Create Order') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush