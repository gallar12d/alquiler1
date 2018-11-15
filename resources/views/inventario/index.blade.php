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
                        <button id="btnCreateProduct" type="button" class="btn btn-default">Crear producto</button>
                    </h2>                
                </div>
            </div>        
        </div>
    </div>    
</div>

<div class="col-md-11">
    <table class="table table-striped datatable">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Talla</th>
                <th>Color</th>
                <th>Referencia</th>
                <th>Estado</th>
                <th>Fecha entrega</th>
                <th>Fecha devolución</th>
                <th>Valor alquiler</th>
                <th>Valor venta</th>
                <th>Linea</th>
                <th>Proveedor</th>
                <th>Foto</th>
                <th>Acciones</th>

            </tr>
            @if(count($productos) >= 1)
        <tbody>
            @foreach($productos as $producto)

            <tr>
                <td>{{$producto->nombre}}</td>
                <td>{{$producto->talla}}</td>
                <td>{{$producto->color}}</td>
                <td>{{$producto->referencia}}</td>
                <td>{{$producto->estado}}</td>
                <td>{{$producto->fecha_entrega}}</td>
                 <td>{{$producto->fecha_devolucion}}</td>
                <td>{{$producto->valor}}</td>
                 <td>{{$producto->valor_venta}}</td>
                <td>{{$producto->linea}}</td>
                @if(isset($producto->proveedor))
                <td>{{$producto->proveedor->nombre}}</td>
                @else
                <td></td>
                @endif
                <td>
                    @if(isset($producto->foto))
                    <a target="_blank" href ="{{asset('fotosProductos/'.$producto->foto)}}  ">Ver foto</a>
                    @else
                    Sin foto
                    @endif
                </td>    
                <td><a href="{{ url('/inventario/editar/'.$producto->id) }}">Editar </a><br>
                    @if(Auth::user()->rol2 == 'superusuario')
                    <a class="eliminarBtn" data-redirect="{{ url('/inventario')}}" href="{{ url('/inventario/eliminar/'.$producto->id) }}"> Eliminar</a></td>        
                    @endif
            </tr>
            @endforeach
            @else
            No existen productos
            @endif

        </tbody>
    </table>
</div>


</div>

<div id="modalCreateProduct" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Crear producto</h4>
            </div>
            <div class="modal-body">
                <form class="formsubmit" method="post" action="{{ url('/inventario/crear') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input required type="text" class="form-control" name ="nombre">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Talla *</label>
                        <input required type="text" class="form-control" name ="talla">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Color</label>
                        <input type="text" class="form-control" name ="color">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Referencia *</label>
                        <input required type="text" class="form-control" name ="referencia">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Estado</label>
                        <select id="estado" class="form-control" name = "estado" >         
                            <option selected value="Disponible">Disponible</option>
                            <option value="lavandería">lavandería</option>
                            <option value="Alquilado">Alquilado</option>
                            <option value ="Prestado">Prestado</option>

                        </select>
                    </div>
                    <div class="form-group fecha_entrega">
                        <label for="nombre">Fecha de entrega</label>
                        <input type="date" class="form-control fecha_entrega" id="fecha_entrega" name ="fecha_entrega">
                    </div>
                    <div class=" fecha_devolucion form-group">
                        <label for="nombre">Fecha de devolución</label>
                        <input  type="date" class="form-control fecha_devolucion" id="fecha_devolucion" name ="fecha_devolucion">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Proveedor</label>
                        <select class="form-control" name = "id_proveedor" >
                            @if(!is_null($proveedores) && isset($proveedores) && count($proveedores) >= 1)
                            @foreach($proveedores as $prov)
                            <option value="{{$prov->id_proveedor}}">{{$prov->nombre}}</option>
                            @endforeach
                            @endif


                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Valor alquiler *</label>
                        <input required type="number" class="form-control" name ="valor">
                    </div>
                    
                     <div class="form-group">
                        <label for="nombre">Valor venta</label>
                        <input  type="number" class="form-control" name ="valor_venta">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Linea</label>
                        <select class="form-control" name = "linea" >
                            <option value="Sin linea">Sin linea</option>          
                            <option value="15 años">15 años</option>
                            <option value="Decoración">Decoración</option>
                            <option value="Novias">Novias</option>
                            <option value ="Gala">Gala</option>
                            <option value ="Caballero">Caballero</option>
                            <option value ="Niños">Niños</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">foto</label>
                        <input id="foto" type="file" class="form-control" name ="foto">
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
    $(document).ready(function () {
        
        $('.fecha_entrega, .fecha_devolucion').hide();
        
        $('select#estado').change(function(){
            var value = $(this).val();
            if(value != 'Prestado'){
                $('.fecha_entrega, .fecha_devolucion').hide();
            }
            else{
                 $('.fecha_entrega, .fecha_devolucion').show();
            }
        });

        $('#btnCreateProduct').click(function (e) {
            $('#modalCreateProduct').modal('show')
        });

        $('form.formsubmit').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            url = form.attr('action');
            method = form.attr('method');
            data = form.serialize();
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
                        alert('Se ha creado el producto exitosamente');
                        $('#modalCreateProduct').modal('hide');
                        location.reload();
                    }
                });
            }
        })

        $('.eliminarBtn').click(function (e) {
            e.preventDefault();
            if (confirm('Está seguro que desea eliminar este registro?')) {
                var url = $(this).attr('href');
                var urlredirect = $(this).attr('data-redirect');
                $.get(url).done(function (data) {
                    if (data) {
                        window.location.href = urlredirect;
                    }
                });
            }

        })

    });
</script>
@endsection
