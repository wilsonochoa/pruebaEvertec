@extends('plantillaBase')
@section('title', 'Listar Ordenes')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <p>Se han produccido los siguientes errores:</p>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Listado de ordenes</h2>
            </div>
            <div class="pull-right mb-2">
                <a class="btn btn-success" href="{{ route('order.newOrder') }}"> Volver a comprar</a>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th width="280px">Estado</th>
            </tr>
        </thead>
        <tbody>
            @php
            $cont = 1;
            @endphp
            @foreach ($orders as $order)
            <tr>
                <td>{{$cont++}}</td>
                <td>{{ $order->users->name }}</td>
                <td>{{ $order->users->email }}</td>
                <td>{{ $order->users->mobile }}</td>
                <td>
                    @if($order->status != '3')
                        <a class="btn btn-warning" href="{{route('viewStateOrden',['id'=>$order->id])}}"> {{ $state[$order->status] }}</a>
                    @else
                        {{ $state[$order->status] }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $orders->links() !!}
</div>

@endsection
