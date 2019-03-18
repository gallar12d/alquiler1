@extends('layouts.app')

@section('content')

<div class = "col-md-8 col-md-offset-2">
    <h2>Editar cliente</h2>
    <form class="formsubmit" method="post"  data-redirect = "{{ url('/persona/c') }}" action="{{ url('/persona/editar') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $persona->id}}">
        <div class="form-group">
            <label for="nombre">Nombre completos *</label>
            <input required type="text" value="{{$persona->name}}" class="form-control" name ="name">
        </div>
        <div class="form-group">
            <label for="nombre">Cedula *</label>
            <input disabled required type="number" value="{{$persona->cedula}}" class="form-control" name ="cedula">
        </div>
        <div class="form-group">
            <label for="nombre">Dirección *</label>
            <input required type="text" class="form-control" value="{{$persona->direccion}}" name ="direccion">
        </div>
        <div class="form-group">
            <label for="nombre">Correo electrónico </label>
            <input  type="email" value="{{$persona->email}}" class="form-control" name ="email">
        </div>
        <div class="form-group">
            <label for="nombre">Teléfono *</label>
            <input required  type="number" value="{{$persona->telefono}}" class="form-control" name ="telefono">
        </div>
        <div class="form-group">
            <label for="nombre">Celular *</label>
            <input required type="number" value="{{$persona->celular}}" class="form-control" name ="celular">
        </div>
        <div class="form-group">
            <label for="nombre">Actividad económica *</label>
            <input required type="text" class="form-control" value="{{$persona->actividad}}" name ="actividad">
        </div>
        <div class="form-group">
            <label for="nombre">Referencia familiar *</label>
            <input required type="text" class="form-control" value="{{$persona->referencia_nombre}}" name ="referencia_nombre">
        </div>
        <div class="form-group">
            <label for="nombre">Celular referencia *</label>
            <input required type="text" class="form-control" value="{{$persona->referencia_celular}}" name ="referencia_celular">
        </div>
        
        <div class="form-group">
            <label for="nombre">Descuento %</label>

            <select class="form-control" name = "descuento" >
                <option <?php echo ($persona->descuento == 0) ? 'selected' : ''; ?> value="0">0%</option>   
                <option <?php echo ($persona->descuento == 10) ? 'selected' : ''; ?> value="10">10%</option>   
                <option <?php echo ($persona->descuento == 15) ? 'selected' : ''; ?> value="15">15%</option>   
                <option <?php echo ($persona->descuento == 20) ? 'selected' : ''; ?> value="20">20%</option> 
            </select>
             
        </div>
        <input type="hidden"   type="text" class="form-control"  value="{{$persona->sexo}}" name ="sexo">
        <input type="hidden" value="c"  type="text" class="form-control"  name ="tipo">
        <input type="hidden"   type="text" class="form-control"  name ="telefono_emergencia">


        <button type="submit" class="btn btn-default">Editar</button>
    </form>


</div>



@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {



        $('form.formsubmit').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            url = form.attr('action');
            method = form.attr('method');
            data = form.serialize();
            // $.post(url, $.param($(this).serializeArray()), function(data) {

            //});

            if (method == 'post') {
                $.post(url, data).done(function (data) {
                    if (data) {
                        alert('Se ha editado el cliente exitosamente');
                        var urlredirect = form.attr('data-redirect')
                        window.location.href = urlredirect;

                    }

                })
            }

        })

    });
</script>
@endsection
