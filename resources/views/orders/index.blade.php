@extends('orders.plantillaBase')
@section('title', 'Listar Ordenes')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Listado de ordenes</h2>
            </div>
            <div class="pull-right mb-2">
                <a class="btn btn-success" href="{{ route('create') }}"> Crear orden</a>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
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
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_email }}</td>
                <td>{{ $order->customer_mobile }}</td>
                <td>{{ $order->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $orders->links() !!}
</div>

@endsection