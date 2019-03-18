
@if(count ($novedades))
<br>
<br>
<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#demo">Ver novedades</button>
<div id="demo" class="collapse ">
    
    <table class="table table-striped">
    <thead>
      <tr>
        <th>Descripción novedad</th>
        <th>Fecha novedad</th>
        
      </tr>
    </thead>
    <tbody>
    @foreach($novedades as $nov)
        <tr>
            
            <td>{{$nov->descripcion}}</td>
            <td>{{$nov->fecha}}</td>
            
        </tr>
        @endforeach
      
    </tbody>
  </table>
</div>

@endif
<h2>Abonos </h2>

<table class="table table-striped">

    <thead>

        <tr>

            <th>Factura número</th>

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>
           

        </tr>

    </thead>

    <tbody>

        @forelse ($facturasAbonos as $fac)
        <tr>

            <td>{{$fac->numero_factura}}</td>

            <td>Primer abono</td>

            @if($fac->metodo_pago === 'Tarjeta')
            
                    @if($fac->estado == 'Anulada')
                     <td><strike>{{$fac->abono}}</strike></td>
                    @else
                     <td class="sumas tarjeta">{{$fac->abono}}</td>   
                    @endif

                

            @else
            
                    @if($fac->estado == 'Anulada')
                     <td><strike>{{$fac->abono}}</strike></td>
                    @else
                      <td class="sumas">{{$fac->abono}}</td>  
                    @endif

           

            @endif



            <td>{{$fac->created_at->format('Y-m-d')}}</td>
            
            @if($fac->estado == 'Anulada')
                     <td><strike>Factura anulada</strike></td>
                    @else
                      <td></td>  
                    @endif



        </tr>

        @empty
        <tr>
        </tr>



        @endforelse
        @forelse ($abonosCompromiso as $fac)
        <tr>

            <td>{{$fac->factura->numero_factura}}</td>

            <td>Abono a compromiso</td>  
            
                    @if($fac->factura->estado == 'Anulada')
                     <td><strike>{{$fac->valor}}</strike></td>
                    @else
                      <td class="sumas">{{$fac->valor}}</td>
                    @endif

           
            <td>{{$fac->fecha}}</td>
            
                    @if($fac->factura->estado == 'Anulada')
                            <td><strike>Factura anulada</strike></td>
                    @else
                             <td</td>
                    @endif



        </tr>

        @empty
        <tr>
        </tr>

        @endforelse



    </tbody>

</table>



<hr>



<h2>Facturas nuevas</h2>

<table class="table table-striped">

    <thead>

        <tr>

            <th>Factura número</th>

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($facturasSaldos as $fac)

        <tr>

            <td>{{$fac->numero_factura}}</td>

            <td>Saldos</td>

            @if($fac->metodo_pago_saldo == 'Tarjeta')

                @if(!is_null($fac->saldo_abonos) || $fac->saldo_abonos === 0)
                    @if($fac->estado == 'Anulada')
                     <td><strike>{{$fac->saldo_abonos}}</strike></td>
                    @else
                     <td class="sumas tarjeta">{{$fac->saldo_abonos}}</td>
                    @endif               
                
                @else
                    @if($fac->estado == 'Anulada')
                     <td><strike>{{$fac->saldo}}</strike></td>
                    @else
                    <td class="sumas tarjeta">{{$fac->saldo}}</td>
                    @endif                   
                
                
                
                @endif
            @else
                @if(!is_null($fac->saldo_abonos) || $fac->saldo_abonos === 0)
                
                    @if($fac->estado == 'Anulada')
                     <td><strike>{{$fac->saldo_abonos}}</strike></td>
                    @else
                      <td class="sumas">{{$fac->saldo_abonos}}</td>
                    @endif  
                
              
                @else
                    @if($fac->estado == 'Anulada')
                     <td><strike>{{$fac->saldo}}</strike></td>
                    @else
                      <td class="sumas">{{$fac->saldo}}</td>
                    @endif  
                
                @endif

            @endif



            <td>{{$fac->created_at->format('Y-m-d')}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>



<hr>



<h2>Garantias</h2>

<table class="table table-striped">

    <thead>

        <tr>

            <th>Detalle</th>
            <th>Valor</th>
            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($garantias as $fac)

        <tr>

            <td>Garantía</td>

            @if(isset($fac->valor))
            <td class="sumas">{{$fac->valor}}</td>
            @else
            <td class="sumas">0</td>
            @endif



            <td>{{$fac->created_at->format('Y-m-d')}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>



<hr>



<h2>Recargos</h2>

<table class="table table-striped">

    <thead>

        <tr>       

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($recargos as $fac)

        <tr>



            <td>{{$fac->concepto}}</td>

            <td class="sumas">{{$fac->valor}}</td>

            <td>{{$fac->created_at->format('Y-m-d')}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>

<hr>

<h2>Ventas</h2>

<table class="table table-striped">

    <thead>

        <tr>       

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($ventas as $fac)

        <tr>



            <td>Venta de productos con factura: {{$fac->numero_factura}}</td>

            @if($fac->metodo_pago == 'Tarjeta')

            <td class="sumas tarjeta">{{$fac->valor}}</td>

            @else

            <td class="sumas">{{$fac->valor}}</td>

            @endif



            <td>{{$fac->fecha_pago}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>



<hr>

<h2>Prestamos</h2>

<table class="table table-striped">

    <thead>

        <tr>       

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($prestamos as $fac)

        <tr>

            <td>Préstamo de producto: {{$fac->producto->nombre}} - ({{$fac->producto->referencia}})</td>


            <td class="sumas">{{$fac->valor}}</td>


            <td>{{$fac->fecha}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>

@if(count($bases))

<hr>

<h2>Base</h2>

<table class="table table-striped">

    <thead>

        <tr>       

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($bases as $fac)

        <tr>



            <td>Ingreso de base</td>

            <td class="sumas base">{{$fac->valor}}</td>

            <td>{{$fac->fecha}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>

@endif

@if(count($abonoscaja))

<hr>

<h2>Abonos a caja</h2>

<table class="table table-striped">

    <thead>

        <tr>       

            <th>Detalle</th>
            <th>Concepto</th>

            <th>Valor</th>

            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($abonoscaja as $fac)

        <tr>



            <td>Abono a caja</td>
            <td>{{$fac->concepto}}</td>

            <td class="sumas">{{$fac->valor}}</td>

            <td>{{$fac->fecha}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>

@endif

<hr>

<h2>Daños</h2>

<table class="table table-striped">

    <thead>

        <tr>       

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($danios as $fac)

        <tr>

            <td>{{$fac->concepto}}</td>

            
            @if($fac->metodo_pago == 'Tarjeta')

             <td class="sumas tarjeta">{{$fac->valor}}</td>

            @else
            <td class="sumas">{{$fac->valor}}</td>
            @endif

            <td>{{$fac->created_at->format('Y-m-d')}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>

<hr>

<h2>Salidas de caja</h2>

<table class="table table-striped">

    <thead>

        <tr>       

            <th>Detalle</th>

            <th>Valor</th>

            <th>Fecha</th>

            <th>Persona</th>

            <th>Identificación</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($salidas as $fac)

        <tr>



            <td>{{$fac->concepto}}</td>

            <td class="restas">- {{$fac->valor}}</td>

            <td>{{$fac->created_at->format('Y-m-d')}}</td>

            <td>{{$fac->nombre_persona}}</td>

            <td>{{$fac->identificacion}}</td>

        </tr>

        @empty

    <p>No existe información</p>

    @endforelse



</tbody>

</table>



<h3>Total Ventas</h3>

<span id="totalventas"></span>

<h3>Total Efectivo</h3>

<span id="totalEfectivo"></span>

<h3>Total Tarjeta</h3>

<span id="totalTarjeta"></span>

<h3>Total Neto </h3>

<span id="total"></span>

<br>

<hr>

@if(count ($novedades))
<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#demo">Novedades</button>
<div id="demo" class="collapse ">
    <br>
    <ul>
        @foreach($novedades as $nov)
        <li>
            {{$nov->descripcion}}
        </li>
        @endforeach
    </ul>
</div>

@endif


@section('scripts')

<script type="text/javascript">

    $(document).ready(function () {



    })

</script>

@endsection







