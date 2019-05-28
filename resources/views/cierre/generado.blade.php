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
<h2>Abonos y facturas viejas </h2>

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


        @forelse ($abonosCompromiso as $fac)
        <tr>

            <td>{{$fac->factura->numero_factura}}</td>

            <td>Abono a compromiso</td>

            @if($fac->factura->estado == 'Anulada')
                <td><strike>{{$fac->valor}}</strike></td>
            @else
                <td class="sumas abono">{{$fac->valor}}</td>
            @endif


            <td>{{$fac->fecha}}</td>

            @if($fac->factura->estado == 'Anulada')
                <td><strike>Factura anulada</strike></td>
            @else
                <td>
                </td>
            @endif
            </tr> 
            @empty 
            <tr>
        </tr>

        @endforelse
        @forelse ($facturasSaldos as $fac)

        <tr>

            <td>{{$fac->numero_factura}}</td>

            <td>Pago total a compromiso</td>

            @if($fac->metodo_pago_saldo == 'Tarjeta')

                @if(!is_null($fac->saldo_abonos) || $fac->saldo_abonos === 0)
                    @if($fac->estado == 'Anulada')
                    <td><strike>{{$fac->saldo_abonos}}</strike></td>
                    @else
                    <td class="sumas  abono tarjeta">{{$fac->saldo_abonos}}</td>
                    @endif

                @else
                    @if($fac->estado == 'Anulada')
                    <td><strike>{{$fac->saldo}}</strike></td>
                    @else
                    <td class="sumas abono tarjeta">{{$fac->saldo}}</td>
                    @endif

            @endif
            @else
                @if(!is_null($fac->saldo_abonos) || $fac->saldo_abonos === 0)

                    @if($fac->estado == 'Anulada')
                    <td><strike>{{$fac->saldo_abonos}}</strike></td>
                    @else
                    <td class="sumas abono">{{$fac->saldo_abonos}}</td>
                    @endif


                @else
                    @if($fac->estado == 'Anulada')
                    <td><strike>{{$fac->saldo}}</strike></td>
                    @else
                    <td class="sumas abono">{{$fac->saldo}}</td>
                    @endif

            @endif

            @endif
            <td>{{$fac->created_at->format('Y-m-d')}}</td>
        </tr>
        @empty


        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalAbonos"> </strong></td>
            
        </tr>
    </tfoot>

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
        @forelse ($facturasAbonos as $fac)
        <tr>

            <td>{{$fac->numero_factura}}</td>

            <td>Primer abono</td>

            @if($fac->metodo_pago === 'Tarjeta')

                @if($fac->estado == 'Anulada')
                    <td><strike>{{$fac->abono}}</strike></td>
                @else
                    <td class="facturaNueva sumas tarjeta">{{$fac->abono}}</td>
                @endif

            @else

                @if($fac->estado == 'Anulada')
                    <td><strike>{{$fac->abono}}</strike></td>
                @else
                    <td class="facturaNueva sumas">{{$fac->abono}}</td>
                @endif

            @endif



            <td>{{$fac->created_at->format('Y-m-d')}}</td>

            @if($fac->estado == 'Anulada')
                <td><strike>Factura anulada</strike></td>
            
            @endif

        </tr>
        @empty
        <tr>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalFacturasNuevas"> </strong></td>
            
        </tr>
    </tfoot>

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
            <td class="sumas garantia">{{$fac->valor}}</td>
            @else
            <td class="sumas garantia">0</td>
            @endif



            <td>{{$fac->created_at->format('Y-m-d')}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalGarantias"> </strong></td>
            
        </tr>
    </tfoot>

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

            <td class="sumas recargo">{{$fac->valor}}</td>

            <td>{{$fac->created_at->format('Y-m-d')}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalRecargos"> </strong></td>
            
        </tr>
    </tfoot>

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

            <td class="sumas venta tarjeta">{{$fac->valor}}</td>

            @else

            <td class="sumas venta">{{$fac->valor}}</td>

            @endif



            <td>{{$fac->fecha_pago}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalVentas"> </strong></td>
            
        </tr>
    </tfoot>

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


            <td class="sumas prestamo">{{$fac->valor}}</td>


            <td>{{$fac->fecha}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalPrestamos"> </strong></td>
            
        </tr>
    </tfoot>

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

            <td class="sumas base basesuma">{{$fac->valor}}</td>

            <td>{{$fac->fecha}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalBases"> </strong></td>
            
        </tr>
    </tfoot>

</table>

@endif

@if(count($abonoscaja))

<hr>

<h2>Ingreso a caja</h2>

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



            <td>Ingreso a caja</td>
            <td>{{$fac->concepto}}</td>

            <td class="sumas abonoCaja">{{$fac->valor}}</td>

            <td>{{$fac->fecha}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalAbonosCaja"> </strong></td>
            
        </tr>
    </tfoot>

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

            <td class="sumas daño tarjeta">{{$fac->valor}}</td>

            @else
            <td class="sumas daño">{{$fac->valor}}</td>
            @endif

            <td>{{$fac->created_at->format('Y-m-d')}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalDaños"> </strong></td>
            
        </tr>
    </tfoot>

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

            <td class="restas resta">- {{$fac->valor}}</td>

            <td>{{$fac->created_at->format('Y-m-d')}}</td>

            <td>{{$fac->nombre_persona}}</td>

            <td>{{$fac->identificacion}}</td>

        </tr>

        @empty

        <p>No existe información</p>

        @endforelse



    </tbody>
    <tfoot>
        <tr>
        <hr>
            <td><strong>Total: </strong><strong id="totalRestas"> </strong></td>
            
        </tr>
    </tfoot>

</table>

<div class="row" style ="border: 2px solid">
    <div class="col-md-3">
        <h3>Total Ventas (sin base)</h3>

        <span id="totalventas"></span>
    </div>
    <div class="col-md-3">
        <h3>Total Ingresos</h3>

        <span id="totalIngresos"></span>
    </div>
    <div class="col-md-3">
        <h3>Total Gastos</h3>

        <span id="totalGastos"></span>
    </div>
    <div class="col-md-3">
        <h3>Total Efectivo</h3>

        <span id="totalEfectivo"></span>
    </div>
    <div class="col-md-3">
        <h3>Bancos</h3>

        <span id="totalTarjeta"></span>
    </div>
    <div class="col-md-3">
        <h3>Total Neto (Con base) </h3>

        <span id="total"></span>
    </div>

</div>
<br>

<hr>




@section('scripts')

<script type="text/javascript">
$(document).ready(function() {



})
</script>

@endsection