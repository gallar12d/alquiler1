@extends('layouts.app')

@section('content')

<div class = "col-md-8 col-md-offset-2">
<h2>Editar proveedor</h2>
<form class="formsubmit" method="post"  data-redirect = "{{ url('/proveedor') }}" action="{{ url('/proveedor/editar') }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="id_proveedor" value="{{ $proveedor->id_proveedor}}">
        <div class="form-group">
          <label for="nombre">Nombre *</label>
          <input required type="text" class="form-control" value = '{{$proveedor->nombre}}' name ="nombre">
        </div>
        <div class="form-group">
          <label for="nombre">Nit </label>
          <input required type="text" value = '{{$proveedor->nit}}' class="form-control" name ="nit">
        </div>
        <div class="form-group">
          <label for="nombre">Cuenta </label>
          <input type="number" class="form-control" value = '{{$proveedor->cuenta}}' name ="cuenta">
        </div>
        <div class="form-group">
          <label for="nombre">Banco</label>
          <input  type="text" class="form-control" value = '{{$proveedor->banco}}' name ="banco">
        </div>
        <div class="form-group">
          <label for="nombre">Ciudad</label>
          <input  type="text" class="form-control" value = '{{$proveedor->ciudad}}' name ="ciudad">
        </div>
       
        
        <button type="submit" class="btn btn-default">Editar</button>
      </form>


</div>

   

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {

 

  $('form.formsubmit').submit(function(e){
    e.preventDefault();    
    var form = $(this);
    url = form.attr('action');
    method = form.attr('method');
    data = form.serialize();
   // $.post(url, $.param($(this).serializeArray()), function(data) {

    //});
    
    if(method == 'post'){
      $.post(url, data).done(function(data){
        if(data){
          alert('Se ha editado el producto exitosamente');          
          var urlredirect = form.attr('data-redirect')
          window.location.href = urlredirect;

        }

      })
    }

  })
    
});
</script>
@endsection
