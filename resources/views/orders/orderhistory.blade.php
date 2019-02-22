@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Order History') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('orderhistory') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-8">
                                <input id="search" type="e" class="form-control{{ $errors->has('search') ? ' is-invalid' : '' }}" name="search" value="{{ old('search') }}" autofocus>
                            
                                @if ($errors->has('search'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('search') }}</strong>
                                    </span>
                                @endif

                            </div>
                            <div class="col-md-4 col-lg-4">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Search') }}
                                </button>
                            </div>
                        </div>             
                    </form>
                    <br>
                        {{ __('Total Order : ').$data->total() }}
                    <br>
                    <!-- {{var_dump($data)}} -->
                    <table class="table">
                      <tbody>
                        @foreach($data as $item)
                          <tr>

                            <td>
                                
                                {{$item->order_no}}   Rp {{$item->total_price}}
                                <br>
                                @if($item->orderable_type=="App\Prepaid")
                                    {{$item->orderable->value}} for {{$item->orderable->mobile_number}}
                                @else
                                    {{$item->orderable->product_name}} that costs {{$item->orderable->price}}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->orderable_type=="App\Prepaid")
                                    @switch($item->paidstatus)
                                        @case(1)
                                            <p class="text-success">Success</p>
                                            @break
                                        @case(2)
                                            <p class="text-warning">Failed</p>
                                            @break
                                        @case(3)
                                            <p class="text-danger">Canceled</p>
                                            @break
                                        @default
                                            <form class="col-md-12 col-lg-12" method="POST" action="{{ route('payment') }}">
                                                @csrf
                                                <input type="hidden" name="data" value='{{$item->order_no}}' />
                                                <button class="btn btn-primary btn-block">
                                                    {{ __('Pay Now') }}</button>
                                            </form>
                                    @endswitch
                                @else  
                                    @switch($item->paidstatus)
                                        @case(1)
                                            <p class="text-success">
                                                shipping code
                                                <br>
                                                {{$item->shipping_code}}
                                            </p>
                                            @break
                                        @case(2)
                                            <p class="text-warning">Failed</p>
                                            @break
                                        @case(3)
                                            <p class="text-danger">Canceled</p>
                                            @break
                                        @default
                                            <form class="col-md-12 col-lg-12" method="POST" action="{{ route('payment') }}">
                                                @csrf
                                                <input type="hidden" name="data" value='{{$item->order_no}}' />
                                                <button class="btn btn-primary btn-block">
                                                    {{ __('Pay Now') }}</button>
                                            </form>
                                    @endswitch    
                                @endif
                                 
                            </td>
                            
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        @if ($data->onFirstPage())
                            <li class="disabled">
                                <a class="btn btn-primary disabled" role="button" aria-disabled="true" href="#" rel="prev" >
                                    {{ __('Prev') }}
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="btn btn-primary" href="{{ $data->previousPageUrl() }}" rel="prev">
                                    {{ __('Prev') }}
                                </a>
                            </li>
                        @endif 


                        <!-- Next Page Link -->
                        @if ($data->hasMorePages())
                            <li>
                                <a class="btn btn-primary" href="{{ $data->nextPageUrl() }}" rel="next" style="margin-left: 10px;">
                                {{ __('Next') }}
                                </a>
                            </li>
                        @else
                            <a class="btn btn-primary disabled" role="button" aria-disabled="true" href="#" rel="prev" style="margin-left: 10px;" >
                                {{ __('Next') }}
                            </a>
                            @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
