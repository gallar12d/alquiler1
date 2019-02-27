@extends('layouts.app')
@section('content')
<div class="row">       

    <div class ="col-md-10 col-md-offset-1">  
        <h3>Compromiso</h3>
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>#Factura</th>
                    <th>Fecha compromiso</th>
                    <th>Fecha devolución</th>
                    <th>Fecha actual</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>    
                    <th>Celular</th>  
                    <th>Valor total factura</th>  
                    <th>Valor primer abono</th>  
                    <th>Saldo</th>  
                </tr>    
            <tbody>
                <tr>      
                    <td>{{$compromiso->factura->numero_factura}}</td>          
                    <td>{{$compromiso->fecha_compromiso}}</td>
                    <td>{{$compromiso->fecha_devolucion}}</td>
                    <td>{{date('d-m-Y')}}</td>
                    <td>{{$compromiso->persona->name}}</td>
                    <td>{{$compromiso->persona->telefono}}</td>
                    <td>{{$compromiso->persona->celular}}</td>  
                    <td>{{$compromiso->factura->valor}}</td>  
                    <td>{{$compromiso->factura->abono}}</td>  
                    @if($compromiso->factura->saldo_abonos || $compromiso->factura->saldo_abonos === 0)
                    <td>{{$compromiso->factura->saldo_abonos}}</td>
                    @elseif($compromiso->factura->saldo || $compromiso->factura->saldo === 0)
                    <td>{{$compromiso->factura->saldo}}</td>
                    @else
                    <td>No existe saldo</td>
                    @endif
                </tr>
            </tbody>
        </table>
        <hr>
        <form  id ="formAbonos" class="col-md-12" method="POST" action="{{url('compromiso/abonar')}}">
            <div class="form-group col-md-8">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <label for="nombre">Ingresar valor del nuevo abono</label>
                <input required type="number"  class="form-control" name ="valor_abono">
                <input  type="hidden"  class="form-control" name ="id_compromiso" value="{{$compromiso->id_compromiso}}">

            </div>
            @if($compromiso->factura->estado == 'Pendiente' &&  $compromiso->factura->saldo != 0)
            <div class="form-group col-md-4">
                <br>
                <button id="btnIngresarAbono" type="submit" class="btn btn-default">Ingresar abono</button>

            </div>
            @endif


        </form>


        <hr>
        <br>

        <h3>Historial de abonos</h3>

       
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Valor</th>
                    <th>Número factura</th>
                </tr>
            </thead>
            <tbody>

                <tr>                
                    
                    <?php
                        $date = new DateTime($compromiso->factura->created_at);
                       
                    ?>

                    
                    <td> {{ $date->format('Y-m-d')}}</td>
                    <td>{{$compromiso->factura->abono}}</td>
                    <td>{{$compromiso->factura->numero_factura}}</td>
                </tr>
                @if(count($compromiso->abonos))
                <?php
                $totalAbonos = 0;
                ?>
                @foreach($compromiso->abonos as $abono)
                <?php
                $totalAbonos = $totalAbonos + $abono->valor;
                ?>
                <tr>
                    <td>{{$abono->fecha}}</td>
                    <td>{{$abono->valor}}</td>
                    <td>{{$compromiso->factura->numero_factura}}</td>
                </tr>
                @endforeach             
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>total abonos</strong></td>
                    <td><strong>{{$totalAbonos + $compromiso->factura->abono}}</strong></td>
                </tr>
            </tfoot>
        </table>
        @else
        <h4></h4>
        @endif
    </div>

</body>




</div>







</div>



@endsection

@section('scripts')

<script type="text/javascript">

    $(document).on('submit', 'form#formAbonos', function () {

        $('#btnIngresarAbono').remove();
    })

</script>

@endsection('scripts')