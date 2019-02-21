@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Prepaid Balance') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('addpayment') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="orderno" class="col-md-4 col-form-label text-md-right">{{ __('Order No') }}</label>

                            <div class="col-md-6">
                                <input id="orderno" type="e" class="form-control{{ $errors->has('orderno') ? ' is-invalid' : '' }}" name="orderno" value="{{ isset($data)? $data : '' }}" required autofocus>

                                @if ($errors->has('orderno'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('orderno') }}</strong>
                                    </span>
                                @endif
                                @if(isset($status))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $status }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bbtn form-group row mb-0">
                            <div class="col-md-4 offset-md-4 col-lg-4">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
