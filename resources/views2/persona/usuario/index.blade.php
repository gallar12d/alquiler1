@extends('layouts.app')

@section('content')
<div class="container"> 
  <div class="row">
    <div class="col-md-12">
      <div class="col-sm-6">
        <h2>Listado de usuarios</h2>
      </div>
      <div class="col-sm-6">
        <div class="text-right">
          <h2>
          <button id="btnCreateUsuario" type="button" class="btn btn-default">Crear usuario</button>
          </h2>                
            </div>
        </div>        
      </div>
    </div>    
  </div>
  <div class="col-md-10 col-md-offset-1">
  <table id="userTable" class="table table-striped datatable ">
    <thead>
    <tr>
    <th>Nombres</th>
    <th>Cédula</th>
    <th>Sexo</th>
    <th>Dirección</th>
     <th>Celular</th>
    <th>Correo</th>
    <th>Rol</th>
    <th>Teléfono de emergencia</th>    
    <th>Acciones</th>    
    </tr>
    @if(count($personas) >= 1)
    <tbody>
      @foreach($personas as $per)
      
      <tr>
          <td>{{$per->name}}</td>
          <td>{{$per->cedula}}</td>
          <td>{{$per->sexo}}</td>
          <td>{{$per->direccion}}</td>
           <td>{{$per->celular}}</td>
          <td>{{$per->email}}</td>
          <td>{{$per->rol}}</td>
          <td>{{$per->telefono_emergencia}}</td>         
          
          <td><a href="{{ url('/persona/editar/'.$per->id.'/u') }}">Editar </a><br><a class="eliminarBtn" data-redirect="{{ url('/persona/u')}}" href="{{ url('/persona/eliminar/'.$per->id) }}"> Eliminar</a></td>        
        </tr>
      @endforeach
    @else
    No existen usuarios
    @endif
     
    </tbody>
  </table>
  </div>
  
</div>


<div id="modalCreateUsuario" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Crear usuario</h4>
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
          <label for="nombre">Sexo</label>
          <select class="form-control" name = "sexo" >
          <option value="M">M</option>          
            <option value="F">F</option>           
          </select>
        </div>
        <div class="form-group">
          <label for="nombre">Dirección</label>
          <input type="text" class="form-control" name ="direccion">
        </div>
        <div class="form-group">
          <label for="nombre">Celular</label>
          <input type="number" class="form-control" name ="celular">
        </div>
        <div class="form-group">
          <label for="nombre">Correo electrónico *</label>
          <input required type="email" class="form-control" name ="email">
        </div>
        <div class="form-group">
        <label for="nombre">Contraseña *</label>
          <input required type="password" class="form-control" name ="password">
        </div>
        <div class="form-group">
          <label for="nombre">Rol</label>
          <select class="form-control" name = "rol" >
          <option value="administrador">Administrador</option>          
            <option value="vendedor">Vendedor</option>           
          </select>
        </div>
        <div class="form-group">
          <label for="nombre">Teléfono de emergencia</label>
          <input  type="number" class="form-control" name ="telefono_emergencia">
        </div>   
        <input type="hidden" value="u"  type="text" class="form-control" name ="tipo">
       
     
        
       
       
        
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
  

  $('#btnCreateUsuario').click(function(e){
    $('#modalCreateUsuario').modal('show')
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
          alert('Se ha creado el usuario exitosamente');
          $('#modalCreateUsuario').modal('hide');
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
