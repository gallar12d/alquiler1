

@if(isset($productos) && count($productos)>= 1 && !is_null($productos))

<table class="table table-striped">

    <thead>

        <tr>



            <th>Producto</th>

            <th>Referencia</th>

            <th>Línea</th>

           
            <th>Imagen</th>
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
                    @if(isset($producto->producto->foto))
                    <td><a target="_blank" href ="{{ asset('fotosProductos/'. $producto->producto->foto)}}">Ver foto</a></td>

                    @else
                    <td>Sin imagen</td>
                    @endif

            @else

            <td></td>

            <td></td>

            @endif

            @if(isset($producto->ajustes))
            <td>{{$producto->ajustes}}</td>
            @else
            <td>No existe ajuste</td>
            @endif



        </tr>

        @endforeach

    </tbody>

</table>

@else

No existen productos 

@endif