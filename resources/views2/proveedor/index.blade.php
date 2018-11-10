@extends('layouts.app')

@section('content')
<div class="container"> 
  <div class="row">
    <div class="col-md-12">
      <div class="col-sm-6">
        <h2>Listado de productos</h2>
      </div>
      <div class="col-sm-6">
        <div class="text-right">
          <h2>
          <button id="btnCreateProveedor" type="button" class="btn btn-default">Crear proveedor</button>
          </h2>                
            </div>
        </div>        
      </div>
    </div>    
  </div>
          
  <table class="table table-striped datatable ">
    <thead>
    <tr>
    <th>Nombre</th>
    <th>Nit</th>
    <th>No cuenta</th>
    <th>Banco</th>
    <th>Ciudad</th>    
    <th>Acciones</th>
    
    </tr>
    @if(count($proveedores) >= 1)
      @foreach($proveedores as $prov)
      <tbody>
      <tr>
          <td>{{$prov->nombre}}</td>
          <td>{{$prov->nit}}</td>
          <td>{{$prov->cuenta}}</td>
          <td>{{$prov->ciudad}}</td>
          <td>{{$prov->banco}}</td>
          
          <td><a href="{{ url('/proveedor/editar/'.$prov->id_proveedor) }}">Editar </a><br><a class="eliminarBtn" data-redirect="{{ url('/proveedor')}}" href="{{ url('/proveedor/eliminar/'.$prov->id_proveedor) }}"> Eliminar</a></td>        
        </tr>
      @endforeach
    @else
    No existen proveedores
    @endif
     
    </tbody>
  </table>
</div>

<div id="modalCreateProveedor" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Crear proveedor</h4>
      </div>
      <div class="modal-body">
      <form class="formsubmit" method="post" action="{{ url('/proveedor/crear') }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
          <label for="nombre">Nombre *</label>
          <input required type="text" class="form-control" name ="nombre">
        </div>
        <div class="form-group">
          <label for="nombre">Nit </label>
          <input  type="text" class="form-control" name ="nit">
        </div>
        <div class="form-group">
          <label for="nombre">Cuenta</label>
          <input type="number" class="form-control" name ="cuenta">
        </div>
        <div class="form-group">
          <label for="nombre">Banco</label>
          <input  type="text" class="form-control" name ="banco">
        </div>
        <div class="form-group">
          <label for="nombre">Ciudad</label>
          <input  type="text" class="form-control" name ="ciudad">
        </div>
        
       
        
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

  $('#btnCreateProveedor').click(function(e){
    $('#modalCreateProveedor').modal('show')
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
          alert('Se ha creado el proveedor exitosamente');
          $('#modalCreateProveedor').modal('hide');
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
