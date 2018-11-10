
<h2>Abonos o facturas viejas</h2>
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
    {{$fac->metodo_pago}}
      <tr>
        <td>{{$fac->numero_factura}}</td>
        <td>Abono</td>
        
        @if($fac->metodo_pago === 'Tarjeta')
          <td class="sumas tarjeta">{{$fac->abono}}</td>        
        @else
         <td class="sumas">{{$fac->abono}}</td>
        @endif
       
        <td>{{$fac->created_at->format('Y-m-d')}}</td>

      </tr>
      @empty
            <p>No existe información</p>
        @endforelse
      
    </tbody>
  </table>

<hr>

<h2>Saldos o facturas nuevas</h2>
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
          <td class="sumas tarjeta">{{$fac->saldo}}</td>
         @else
         <td class="sumas">{{$fac->saldo}}</td>
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
        <td class="sumas">{{$fac->valor}}</td>
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
        <td class="sumas">{{$fac->valor}}</td>
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

  <h3>Total</h3>
  <span id="total"></span>
  <h3>Total Efectivo</h3>
  <span id="totalEfectivo"></span>
  <h3>Total Tarjeta</h3>
  <span id="totalTarjeta"></span>
  @section('scripts')
<script type="text/javascript">
$(document).ready(function() {

})
</script>
@endsection



