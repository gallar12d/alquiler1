
@if(isset($productos) && count($productos)>= 1 && !is_null($productos))
<table class="table table-striped">
    <thead>
      <tr>
        
        <th>Producto</th>
        <th>Referencia</th>
        <th>Línea</th>
        <th>Ajuste</th>
      </tr>
    </thead>
    
    <tbody>
    @foreach($productos as $producto)
    
      <tr>

        @if(isset($producto->producto))        
        <td>{{$producto->producto->nombre}}</td>
        <td>{{$producto->producto->referencia}}</td>
          <td>{{$producto->producto->linea}}</td>
        @else
        <td></td>
        <td></td>
        @endif
        <td>{{$producto->ajustes}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  No existen productos 
  @endif