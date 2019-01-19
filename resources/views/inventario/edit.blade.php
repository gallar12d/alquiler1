@extends('layouts.app')

@section('content')

<div class = "col-md-8 col-md-offset-2">
    <h2>Editar producto</h2>
    <form class="formsubmit" method="post"  data-redirect = "{{ url('/inventario') }}" action="{{ url('/inventario/editar') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $producto->id}}">
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input required type="text" class="form-control" value = '{{$producto->nombre}}' name ="nombre">
        </div>
        <div class="form-group">
            <label for="nombre">Talla *</label>
            <input required type="text" value = '{{$producto->talla}}' class="form-control" name ="talla">
        </div>
        <div class="form-group">
            <label for="nombre">Color </label>
            <input type="text" class="form-control" value = '{{$producto->color}}' name ="color">
        </div>
        <div class="form-group">
            <label for="nombre">Referencia *</label>
            <input required type="text" class="form-control" value = '{{$producto->referencia}}' name ="referencia">
        </div>
        <div class="form-group">
            <label for="nombre">Estado</label>
            <select <?php echo ($producto->estado == 'Comprometido' || $producto->estado == 'Alquilado' )? 'disabled':'' ?> id='estado' class="form-control" name = "estado" >
                <option <?php echo ($producto->estado == 'Disponible') ? "selected" : '' ?> value="Disponible">Disponible</option>
                <option <?php echo ($producto->estado == 'lavandería') ? "selected" : '' ?> value="lavandería">lavandería</option>
                <option <?php echo ($producto->estado == 'Alquilado') ? "selected" : '' ?> value="Alquilado">Alquilado</option>
                <option <?php echo ($producto->estado == 'Prestado') ? "selected" : '' ?> value ="Prestado">Prestado</option>
                <option <?php echo ($producto->estado == 'Comprometido') ? "selected" : '' ?> value ="Comprometido">Comprometido</option>

            </select>
        </div>
        <div class="form-group fecha_entrega">
            <label for="nombre">Fecha de entrega</label>
            <input value ="{{$producto->fecha_entrega}}" type="date" class="form-control fecha_entrega" id="fecha_entrega" name ="fecha_entrega">
        </div>
        <div class=" fecha_devolucion form-group">
            <label for="nombre">Fecha de devolución</label>
            <input value ="{{$producto->fecha_devolucion}}"  type="date" class="form-control fecha_devolucion" id="fecha_devolucion" name ="fecha_devolucion">
        </div>
         
        <div class="form-group">
            <label for="nombre">Valor alquiler *</label>           
            <input <?php echo (Auth::user()->rol2 == 'superusuario') ? '' : 'disabled' ?> required type="number" value = '{{$producto->valor}}' class="form-control" name ="valor">
         
        </div>
        <div class="form-group">
            <label for="nombre">Valor venta </label>         
            <input <?php echo (Auth::user()->rol2 == 'superusuario') ? '' : 'disabled' ?> required type="number" value = '{{$producto->valor_venta}}' class="form-control" name ="valor_venta">
         
        </div>
        <div class="form-group">
            <label for="nombre">Linea</label>
            <select class="form-control" name = "linea" >
                <option <?php echo ($producto->linea == 'Sin linea') ? "selected" : '' ?> value="Sin linea">Sin linea</option>          
                <option  <?php echo ($producto->linea == '15 años') ? "selected" : '' ?>  value="15 años">15 años</option>
                <option  <?php echo ($producto->linea == 'Decoración') ? "selected" : '' ?> value="Decoración">Decoración</option>
                <option  <?php echo ($producto->linea == 'Novias') ? "selected" : '' ?> value="Novias">Novias</option>
                <option  <?php echo ($producto->linea == 'Gala') ? "selected" : '' ?> value ="Gala">Gala</option>
                <option  <?php echo ($producto->linea == 'Caballero') ? "selected" : '' ?> value ="Caballero">Caballero</option>
                <option  <?php echo ($producto->linea == 'Niños') ? "selected" : '' ?> value ="Niños">Niños</option>
            </select>
        </div>
        <div class="form-group">
            <label for="nombre">Proveedor</label>
            <select class="form-control" name = "id_proveedor" >
                @if(!is_null($proveedores) && isset($proveedores) && count($proveedores) >= 1)

                @foreach($proveedores as $prov)
                <option <?php echo ($producto->id_proveedor == $prov->id_proveedor) ? 'selected' : ''; ?> value="{{$prov->id_proveedor}}">{{$prov->nombre}}</option>
                @endforeach
                @endif

            </select>
        </div>
        <div class="form-group">
            <label for="nombre">foto</label>
            <input type="file" class="form-control"  name ="foto">
            {{$producto->foto}}
        </div>

        <button type="submit" class="btn btn-default">Editar</button>
    </form>


</div>



@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('select#estado').change(function () {
            var value = $(this).val();
            if (value != 'Prestado') {
                $('.fecha_entrega, .fecha_devolucion').hide();
                $('input.fecha_entrega, input.fecha_devolucion').attr('value', null);
            } else {
                $('.fecha_entrega, .fecha_devolucion').show();
            }
        });

        $('select#estado')                
                .trigger('change');



        $('form.formsubmit').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            url = form.attr('action');
            method = form.attr('method');
            data = form.serialize();
            // $.post(url, $.param($(this).serializeArray()), function(data) {

            //});

            var formData = new FormData($(this)[0]);

            if (method == 'post') {

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false
                }).done(function (data) {
                    if (data) {
                        alert('Se ha editadp el producto exitosamente');
                        var urlredirect = form.attr('data-redirect')
                        window.location.href = urlredirect;

                    }

                })
            }

        })

    });
</script>
@endsection
