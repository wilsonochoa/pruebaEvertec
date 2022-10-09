@extends('plantillaBase')
@section('title', 'Comprar producto')

@section('content')
<div >

    <div class="col-12 text-center">
        <a href="{{route('order.create')}}"><img class="img-fluid img-portfolio img-hover mb-3" src="{{asset('img/control.webp')}}" alt="DualShock Controller for PlayStation 4"></a>
        <div>
            <h3><a href="{{route('order.create')}}">DualShock Controller for PlayStation 4</a></h3>
            <div class="mb-2">
                $20.000
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <a href="{{route('order.lstorder')}}" class="btn btn-primary" >Listar ordenes</a>
</div>

@endsection
