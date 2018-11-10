@extends('layouts.app')
@section('content')
<div class="col-md-10 col-md-offset-1">

    <h3>Compromiso</h3>
    <table class="table table-striped ">
        <thead>
            <tr>
                <th>#Factura</th>
                <th>Fecha compromiso</th>
                <th>Cliente</th>
                <th>Teléfono</th>    
                <th>Celular</th>     
            </tr>    
        <tbody>
            <tr>      
                <td>{{$compromiso->factura->numero_factura}}</td>          
                <td>{{$compromiso->fecha_compromiso}}</td>
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
    <form class="formsubmit" method="post" action="{{ url('/compromiso/penalizar') }}">
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
        
        <?php 
        $mitad = intval($factura->valor) / 2 ;   
        $valor_devolucion = 0;
        if($factura->abono > $mitad){
            
            $valor_devolucion = $factura->abono - $mitad;
        }
        ?>
        <div class="form-group col-md-6 ">
            <label for="nombre">Valor devolución permitida </label>
            <input   value = "{{$valor_devolucion}}"  type="number" class="form-control"  name ="valor_devolucion" readonly>
        </div> 
        
      
        
        <div class="row">
            <div class ="col-md-12"> 
              
                <button type ="submit"  class="btnEntrega pull-right col-md-3 btn btn-default ">Penalizar</button>
               
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
            e.preventDefault();
            if (confirm('Está seguro que desea realizar la penalización de este compromiso?')) {
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
