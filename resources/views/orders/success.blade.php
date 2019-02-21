@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Success!') }}</div>

                <div class="card-body">
                    @if(count($data)>3)
                       Order No. {{$data[0]}}
                      <br>
                      Total Rp {{$data[3]}}
                      <br>
                      <br>
                      {{$data[1]}} that costs {{$data[3]}} will be shipped to :
                      <br>
                      {{$data[2]}}
                      <br>
                      only after you pay
                    @else
                      Order No. {{$data[0]}}
                      <br>
                      Total Rp {{$data[2]}}
                      <br>
                      <br>
                      Your mobile phone number {{$data[1]}} will receive Rp {{$data[2] * 100 / (100+5)}}
                    @endif
                    
                    <div class="bbtn" style="margin-right: -15px;margin-left: -15px;">
                      <form class="col-md-4 offset-md-4 col-lg-4" method="POST" action="{{ route('payment') }}">
                          @csrf
                          <input type="hidden" name="data" value='{{$data[0]}}' />
                          <button class="btn btn-primary btn-block">
                              {{ __('Pay Now') }}</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
