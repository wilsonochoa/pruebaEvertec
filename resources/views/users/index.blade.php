@extends('plantillaBase')
@section('title', 'Comprar producto')

@section('content')
<div class='row'>
    <div class="col-md-3 col-6 mx-auto">
        <a href="{{route('preview')}}"><img class="img-fluid img-portfolio img-hover mb-3" src="{{asset('img/control.webp')}}" alt="DualShock Controller for PlayStation 4"></a>
        <div>
            <h3><a href="{{route('preview')}}">DualShock Controller for PlayStation 4</a></h3>
            <div class="price-mob mb-2">
                $20.000
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

@endsection