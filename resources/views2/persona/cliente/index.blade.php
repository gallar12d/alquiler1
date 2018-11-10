@extends('layouts.app')

@section('content')
<div class="container"> 
  <div class="row">
    <div class="col-md-12">
      <div class="col-sm-6">
        <h2>Listado de clientes</h2>
      </div>
      <div class="col-sm-6">
        <div class="text-right">
          <h2>
          <button id="btnCreateCliente" type="button" class="btn btn-default">Crear cliente</button>
          </h2>                
            </div>
        </div>        
      </div>
    </div>    
  </div>

  <div class="col-md-11 col-md-offset-0.5" >
  <table class="table table-striped datatable">
    <thead>
    <tr>
    <th>Nombres</th>
    <th>Cédula</th>
    <th>Dirección</th>
    <th>Correo</th>
    <th>Teléfono</th>    
    <th>Celular</th>
    <th>Actividad económica</th>
    <th>Referencia familiar</th>
    <th>Celular referencia</th>    
    <th>Acciones</th>    
    
    
    </tr>
    @if(count($personas) >= 1)
    <tbody>
      @foreach($personas as $per)
      
      <tr>
          <td>{{$per->name}}</td>
          <td>{{$per->cedula}}</td>
          <td>{{$per->direccion}}</td>
          <td>{{$per->email}}</td>
          <td>{{$per->telefono}}</td>
          <td>{{$per->celular}}</td>
          <td>{{$per->actividad}}</td>
          <td>{{$per->referencia_nombre}}</td>
          <td>{{$per->referencia_celular}}</td>
        
          
          <td><a href="{{ url('/persona/editar/'.$per->id.'/c') }}">Editar </a><br><a class="eliminarBtn" data-redirect="{{ url('/persona/c')}}" href="{{ url('/persona/eliminar/'.$per->id) }}"> Eliminar</a></td>        
        </tr>
      @endforeach
    @else
    No existen clientes
    @endif
     
    </tbody>
  </table>
  </div>
          
  
</div>

<div id="modalCreateCliente" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Crear cliente</h4>
      </div>
      <div class="modal-body">
      <form class="formsubmit" method="post" action="{{ url('/persona/crear') }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
          <label for="nombre">Nombre completos *</label>
          <input required type="text" class="form-control" name ="name">
        </div>
        <div class="form-group">
          <label for="nombre">Cedula *</label>
          <input required type="number" class="form-control" name ="cedula">
        </div>
        <div class="form-group">
          <label for="nombre">Dirección</label>
          <input type="text" class="form-control" name ="direccion">
        </div>
        <div class="form-group">
          <label for="nombre">Correo electrónico *</label>
          <input required type="email" class="form-control" name ="email">
        </div>
        <div class="form-group">
          <label for="nombre">Teléfono</label>
          <input  type="number" class="form-control" name ="telefono">
        </div>
        <div class="form-group">
          <label for="nombre">Celular *</label>
          <input required type="number" class="form-control" name ="celular">
        </div>
        <div class="form-group">
          <label for="nombre">Actividad económica</label>
          <input  type="text" class="form-control" name ="actividad">
        </div>
        <div class="form-group">
          <label for="nombre">Referencia familiar *</label>
          <input required type="text" class="form-control" name ="referencia_nombre">
        </div>
        <div class="form-group">
          <label for="nombre">Celular referencia *</label>
          <input required type="number" class="form-control" name ="referencia_celular">
        </div>
        <input type="hidden"   type="text" class="form-control" name ="sexo">
        <input type="hidden" value="c"  type="text" class="form-control" name ="tipo">
        <input type="hidden"   type="text" class="form-control" name ="telefono_emergencia">
     
        
       
       
        
        <button type="submit" class="btn btn-default">Crear</button>
      </form>
      </div>
    
    </div>

  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {

  $('#btnCreateCliente').click(function(e){
    $('#modalCreateCliente').modal('show')
  })

  $('form.formsubmit').submit(function(e){
    e.preventDefault();    
    var form = $(this);
    url = form.attr('action');
    method = form.attr('method');
    data = form.serialize();
   // $.post(url, $.param($(this).serializeArray()), function(data) {

    //});
    console.log(method, url)

    if(method == 'post'){
      $.post(url, data).done(function(data){
        if(data){
          alert('Se ha creado el cliente exitosamente');
          $('#modalCreateCliente').modal('hide');
          location.reload();

        }

      })
    }

  })

  $('.eliminarBtn').click(function(e){
    e.preventDefault();

    if(confirm('Está seguro que desea eliminar este registro?')){
      var url = $(this).attr('href');
      var urlredirect = $(this).attr('data-redirect');
      
      $.get(url).done(function(data){
        if(data){
        
          window.location.href = urlredirect;
        }
      })
    }

  })
    
});
</script>
@endsection
