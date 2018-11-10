@extends('layouts.app')

@section('content')

<div class="row">
    <div class ="col-md-10 col-md-offset-1">
    <h3>Crear salida de caja</h3>
    <form class="formsubmit" method="post" action="{{ url('/salida/crear') }}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       
        <div class="form-group">
            <label for="nombre">Concepto *</label>
            <input required type="text" class="form-control" name ="concepto">
        </div>
        <div class="form-group">
            <label for="nombre">Valor *</label>
            <input required type="number" class="form-control" name ="valor">
        </div>
         <div class="form-group">
            <label for="nombre">Nombre Persona</label>
            <input  type="text" class="form-control" name ="persona">
        </div>
         <div class="form-group">
            <label for="nombre">Identificación</label>
            <input  type="text" class="form-control" name ="identificacion">
        </div>           
        
        <button type="submit" class="btn btn-default">Crear</button>
        </form>
    </div>

</div>


      @endsection