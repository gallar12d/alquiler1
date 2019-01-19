@extends('layouts.app')

@section('content')

<div class="container">
  <h2>Productos pendientes</h2>
  <p>Listado de productos con fecha de préstamo mayor o igual a 9 días:</p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Referencia</th>
        <th>Sede</th>
        <th>Fecha prestamo</th>
      </tr>
    </thead>
    <tbody>
        @if(count($pendientes))
        @foreach($pendientes as $prestamo)
        <tr>
        <td>{{$prestamo->producto->nombre}} </td>
        <td>{{$prestamo->producto->referencia}}</td>
        <td>{{$prestamo->sede->nombre}}</td>
         <td>{{$prestamo->fecha}}</td>
      </tr>
        
        @endforeach
        @endif
      
   
    </tbody>
  </table>
</div>







@endsection

@section('scripts')



@endsection

