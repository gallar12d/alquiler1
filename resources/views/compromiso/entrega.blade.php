@extends('layouts.app')
@section('content')
<div class="col-md-10 col-md-offset-1">

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
            </tr>
        </tbody>
    </table>

    <hr />
    @if(isset($productos) && count($productos)>= 1 && !is_null($productos))
    <h3>Productos</h3>
    <table class="table table-striped">
        <thead>
            <tr>

                <th>Producto</th>
                <th>Referencia</th>
                <th>Ajuste</th>
            </tr>
        </thead>

        <tbody>
            @foreach($productos as $producto)

            <tr>

                @if(isset($producto->producto))        
                <td>{{$producto->producto->nombre}}</td>
                <td>{{$producto->producto->referencia}}</td>
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
    <hr />
    <form class="formsubmit" method="post" action="{{ url('/compromiso/entregar') }}">
        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <input  type="hidden" name="id_factura" value="{{ $factura->id_factura }}">
        <input  type="hidden" name="id_compromiso" value="{{ $compromiso->id_compromiso }}">

        <div class="form-group col-md-6 ">
            <label for="nombre">Valor total</label>
            <input   value = "{{$factura->valor}}"  type="number" class="form-control"  name ="valor" disabled>
        </div>  

        <div class="form-group col-md-6 ">
            <label for="nombre">Abono </label>
            <input   value = "{{$factura->abono}}"  type="number" class="form-control"  name ="abono" disabled>
        </div> 
        <div class="form-group col-md-6 ">
            <label for="nombre">Depósito del Saldo</label>
            <input readonly="readonly" id="deposito_saldo"  type="number" class="form-control" value="{{$factura->saldo}}"  name ="deposito_saldo" >
        </div> 
        <div class="form-group col-md-6">
            <label for="nombre">Tipo de pago</label>
            <select id="tipo_pago" class="form-control" name = 'tipo_pago' >
                <option value="Efectivo">Efectivo</option>          
                <option value="Tarjeta">Tarjeta</option>           
            </select>
        </div> 
        <div class="form-group col-md-6">
            <label for="nombre">Tipo de garantía</label>
            <select  class="form-control garantiaSelect" name = 'tipo_garantia' >
                <option value="No">No aplica</option>  
                <option value="dinero">Dinero</option>  
                            
            </select>
        </div> 
        <div hidden  class="form-group col-md-6  valor_garantia">
            <label for="nombre">Valor de garantía en dinero</label>
            <input id="valor_garantia"  type="number" class=" valor_garantia form-control"   name ="valor_garantia" >
        </div> 
        <div class="form-group col-md-6">
            <label for="nombre">Tipo de garantía 2</label>
            <select  class="form-control garantiaSelect2" name = 'tipo_garantia2' >
                <option value="No">No aplica</option>  
                <option value="Cedula">Cédula</option>          
                <option value="Libreta militar">libreta militar</option>  
                <option value="Tarjeta profesional">Tarjeta profesional</option>                  
            </select>
        </div>
        <div class="row">
            <div class ="col-md-12">               
                <button type ="submit"  class="btnEntrega pull-right col-md-3 btn btn-default ">Hacer entrega</button>
            </div>  

        </div> 
        <br>

    </form>
    <br>
</div>

<!-- Modal agregar productos-->


@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('button.btnEntrega').on('click', function (e) {
            var elemento = $(this);
            e.preventDefault();
            if (confirm('Está seguro que se han entregado todos los productos al cliente, y que cuente con el dinero del compromiso en sus manos?')) {
                
                elemento.remove();
                 $('form.formsubmit').submit();
            }
        });
        

        $('select.garantiaSelect').change(function () {
            var value = $(this).val();

            if (value != 'dinero') {
                $('.valor_garantia').hide();
                $('input.valor_garantia').attr('value', null);
            } else {
                $('.valor_garantia').show();
            }
        })


    })




</script>
@endsection
