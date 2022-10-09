@extends('plantillaBase')
@section('title', 'Previsualizar Ordenes')

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
    <div class="container">
        <div style="display: flex;
  align-items: center;
  justify-content: center;">
            <form method="POST" action="{{ route('order.createProcess') }}">
                @csrf

                <legend>Datos necesarios</legend>
                <div class="form-group">
                    <label for="name" class="form-label">Nombre *</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Ingrese su nombre"
                           maxlength="80" required @if($order) value="{{$order->users->name}}" @endif>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="Ingrese su correo electr&oacute;nico" maxlength="120" required
                           @if($order) value="{{$order->users->email}}" @endif>
                </div>
                <div class="form-group">
                    <label for="mobile" class="form-label">Celular *</label>
                    <input type="number" id="mobile" name="mobile" class="form-control"
                           placeholder="Ingrese su numero de celular" maxlength="40" required
                           @if($order)value="{{$order->users->mobile}}"@endif>
                </div>
                <div>
                    <input type="hidden" name="id_order" @if($order)value="{{$order->id}}"@endif>
                    <a href="{{route('order.newOrder')}}" type="submit" class="btn btn-primary m-5">Volver</a>
                    <button type="submit" class="btn btn-primary m-5">Crear orden</button>
                </div>
            </form>
        </div>
    </div>
@endsection
