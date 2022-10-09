@extends('plantillaBase')
@section('title', 'Estado orden')
@section('content')
    <div class=''>
        <div class="col-12 text-center">
            <img class="img-fluid img-portfolio img-hover mb-3" src="{{asset('img/control.webp')}}"
                 alt="DualShock Controller for PlayStation 4" style="width: 250px;">
            <div>
                <h3>DualShock Controller for PlayStation 4</h3>
                <div class="mb-2">
                    $20.000
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class=" align-items-center">
        <div class="col-12 text-center">
            <h2>Estado del producto</h2>
        </div>
        <div class="col-12 text-center">
            <label> Estado del producto: <strong>{{$statesTra[$response['status']['status']]}}</strong> </label>
        </div>
        <div class="col-12 text-center">
            <label> Detalles: {{$response['status']['message']}} </label>
        </div>
        @if($response['status']['status'] == 'REJECTED')
            <div class="col-12 text-center p-2">
                <a class="btn btn-danger" href="{{route("retryPayOrder", ['id' => $order->id])}}" role="button">Reintentar</a>
            </div>
        @endif
        @if($response['status']['status'] == 'PENDING')
            <div class="col-12 text-center p-2">
                <a class="btn btn-warning" href="{{$order->process_url}}" role="button">Reintentar</a>
            </div>
        @endif
        <div class="col-12 text-center p-2">
            <a href="{{route('order.lstorder')}}" class="btn btn-primary" >Listar ordenes</a>
        </div>
    </div>
@endsection
