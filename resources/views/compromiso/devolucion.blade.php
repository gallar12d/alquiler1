@extends('layouts.app')

@section('content')

<div class="col-md-10 col-md-offset-1">



    <h3>Compromiso</h3>

    <table class="table table-striped ">

    <thead>

    <tr>

    <th>#Factura</th>

    <th>Fecha compromiso</th>

    <th>Fecha devolucioón</th>
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

    <form  id ="formDevolucion" class="formsubmit" method="post" action="{{ url('/compromiso/devolucion') }}">

        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">

        <input  type="hidden" name="id_factura" value="{{ $factura->id_factura }}">

        <input  type="hidden" name="id_compromiso" value="{{ $compromiso->id_compromiso }}">

        

        <div class="form-group col-md-6 ">

            <label for="nombre">Valor total</label>

            <input   value = "{{$factura->valor}}"  type="number" class="form-control"  name ="valor" disabled>

        </div>  

           

        <div class="form-group col-md-6 ">

            <label for="nombre">Abono </label>
            
            @if(count($compromiso->abonos))
                <?php 
                
                $valorTotalAbonos = 0;
                foreach($compromiso->abonos as $abono){
                    $valorTotalAbonos = $valorTotalAbonos + $abono->valor;
                                      
                }                
                $valorTotalAbonos = $valorTotalAbonos + $factura->abono;
                ?>
            <input   value = "{{$valorTotalAbonos}}"  type="number" class="form-control"  name ="abono" disabled>
           
            @else
             <input   value = "{{$factura->abono}}"  type="number" class="form-control"  name ="abono" disabled>
           
            @endif


        </div> 

        <div class="form-group col-md-6 ">
            

            <label for="nombre">Depósito del Saldo</label>
            
            @if(count($compromiso->abonos))
            <input disabled id="deposito_saldo"  type="number" class="form-control" value="{{$factura->saldo_abonos}}"  name ="deposito_saldo" >

            @else
            <input disabled id="deposito_saldo"  type="number" class="form-control" value="{{$factura->saldo}}"  name ="deposito_saldo" >

            @endif

            
        </div> 

        <div class="form-group col-md-6">
          <label for="nombre">Prenda en buenas condiciones</label>
          <select id="malas_condiciones" class="form-control" name = 'malas_condiciones' >
          <option value="si">Sí</option>  
            <option value="no">No</option>     
          </select>

        </div> 

        <div class="form-group col-md-6">

            <label for="comment">Estado y condiciones del producto</label>

            <textarea  disabled class="form-control"  id="condiciones" name ="condiciones"></textarea>

        </div>

        <div class="form-group col-md-6 ">

            <label for="nombre">Valor del daño</label>

            <input disabled id="valor_danio"  type="number" class="form-control"   name ="valor_danio" >

        </div> 

        <div class="form-group col-md-12 ">

            <label for="nombre">{{$textrecargo}}</label>

            <input readonly  id="recargo"  type="number" class="form-control"  value="{{$recargo}}" name ="recargo" >

        </div> 

        @if(Auth::user()->rol == 'administrador')

        <div class="form-group col-md-6 ">

           <label class="checkbox-inline"><input type="checkbox" name="anular" value="1">Anular Recargo</label>

        </div> 

        @endif   

        <div class="row">

            <div class ="col-md-12">               

                <button  id="btnSubmit" type ="submit"  class=" pull-right col-md-3 btn btn-default ">Reintegrar productos</button>

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
        
      $(document).on('submit', 'form#formDevolucion', function(){
          
          $('#btnSubmit').hide();
      });  

      $(document).on('change', 'select#malas_condiciones', function(){



          

          if($(this).val() == 'no'){



           $('textarea#condiciones').prop('disabled', false);

           $('textarea#condiciones').attr('disabled', false); 

           $('input#valor_danio').prop('disabled', false);

           $('input#valor_danio').attr('disabled', false); 



     

          }

          else{

            $('textarea#condiciones').prop('disabled', true);

           $('textarea#condiciones').attr('disabled', true); 

           $('input#valor_danio').prop('disabled', true);

           $('input#valor_danio').attr('disabled', true); 

          }

      })

       

    })



   



   

</script>

@endsection

