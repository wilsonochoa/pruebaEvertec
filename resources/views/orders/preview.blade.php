@extends('plantillaBase')
@section('title', 'Previsualizar Ordenes')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <p>Corrige los siguientes errores:</p>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<h2>Resumen del producto</h2>
<div class='row'>
    <div class="col-md-3 col-6 mx-auto">
        <img class="img-fluid img-portfolio img-hover mb-3" src="{{asset('img/control.webp')}}" alt="DualShock Controller for PlayStation 4">
        <div>
            <h3>DualShock Controller for PlayStation 4</h3>
            <div class="price-mob mb-2">
                $20.000
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<form method="POST" action="{{ route('order.previewProcess') }}">
    @csrf
    <fieldset >
        <legend>Datos necesarios</legend>
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Ingrese su nombre" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Ingrese su correo electr&oacute;nico" required>
        </div>
        <div class="mb-3">
            <label for="mobile" class="form-label">Celular *</label>
            <input type="number" id="mobile" name="mobile" class="form-control" placeholder="Ingrese su numero de celular" required>
        </div>
        <button type="submit" class="btn btn-primary">Comprar</button>
    </fieldset>
</form>
@endsection
