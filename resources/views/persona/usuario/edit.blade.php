@extends('layouts.app')



@section('content')



<div class = "col-md-8 col-md-offset-2">

<h2>Editar cliente</h2>

<form class="formsubmit" method="post"  data-redirect = "{{ url('/persona/u') }}" action="{{ url('/persona/editar') }}">

      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <input type="hidden" name="id" value="{{ $persona->id}}">

      <div class="form-group">

          <label for="nombre">Nombre completos *</label>

          <input required type="text" class="form-control" value = "{{$persona->name}}" name ="name">

        </div>

        <div class="form-group">

          <label for="nombre">Cedula *</label>

          <input required type="number" class="form-control"  value = "{{$persona->cedula}}" name ="cedula">

        </div>

        <div class="form-group">

          <label for="nombre">Sexo</label>

          <select class="form-control" name = "sexo" >

          <option <?php  echo ($persona->sexo == 'M') ? 'selected':'';   ?> value="M">M</option>          

            <option <?php  echo ($persona->sexo == 'F') ? 'selected':'';   ?> value="F">F</option>           

          </select>

        </div>

        <div class="form-group">

          <label for="nombre">Dirección</label>

          <input type="text" class="form-control"  value = "{{$persona->direccion}}" name ="direccion">

        </div>

         <div class="form-group">

          <label for="nombre">Celular</label>

          <input type="number" class="form-control"  value = "{{$persona->celular}}" name ="celular">

        </div>

        <div class="form-group">

          <label for="nombre">Correo electrónico *</label>

          <input required type="email" class="form-control"  value = "{{$persona->email}}" name ="email">

        </div>

       

        <div class="form-group">

          <label for="nombre">Rol</label>

          <select class="form-control" name = "rol" >

          <option  <?php  echo ($persona->rol == 'administrador') ? 'selected':'';   ?> value="administrador">Administrador</option>          

            <option <?php  echo ($persona->rol == 'vendedor') ? 'selected':'';   ?> value="vendedor">Vendedor</option>           

          </select>

        </div>

        <div class="form-group">

          <label for="nombre">Teléfono de emergencia</label>

          <input  type="number" class="form-control"   value = "{{$persona->telefono_emergencia}}" name ="telefono_emergencia">

        </div>   

        <input type="hidden" value="u"  type="text" class="form-control" name ="tipo">

        

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

          alert('Se ha editado el usuario exitosamente');          

          var urlredirect = form.attr('data-redirect')

          window.location.href = urlredirect;



        }



      })

    }



  })

    

});

</script>

@endsection

