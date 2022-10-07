@extends('orders.plantillaBase')
@section('title', 'Listar Ordenes')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Laravel 9 CRUD Example Tutorial</h2>
            </div>
            <div class="pull-right mb-2">
                <a class="btn btn-success" href="{{ route('orders.create') }}"> Crear orden</a>
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
                <th>S.No</th>
                <th>Company Name</th>
                <th>Company Email</th>
                <th>Company Address</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $cont = 1;
            @endphp
            @foreach ($orders as $order)
            <tr>
                <td>{{$cont++}}</td>
                <td>{{ $company->customer_name }}</td>
                <td>{{ $company->customer_email }}</td>
                <td>{{ $company->customer_mobile }}</td>
                <td>{{ $company->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $orders->links() !!}
</div>

@endsection