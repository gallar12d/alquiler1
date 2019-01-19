@extends('layouts.app')

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-12">
            <div class="col-sm-6">
                <h2>Listado de compromisos</h2>
            </div>
            <div class="col-sm-6">
                <div class="text-right">
                    <h2>
                        <a href="{{url('/compromiso/create')}}" class="btn btn-default" role="button">Crear compromiso</a>

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
                <th>#Factura</th>
                <th>Estado</th>
                <th>Fecha compromiso</th>
                <th>Fecha devolución</th>
                <th>Cliente</th>
                <th>Cédula</th>
                <th>Teléfono</th>    
                <th>Celular</th>  
                <th>Acciones</th>  
                <th>Abonos</th> 
                <th>Ajustes establecidos</th>


            </tr>
            @if(count($compromisos) >= 1)
        <tbody>
            @foreach($compromisos as $com)

            <tr>
                @if(isset($com->factura))
                <td>{{$com->factura->numero_factura}}</td>

                @if($com->estado == 'Devuelto')
                <td>Devuelto</td>
                @elseif($com->factura->estado == 'Pagada')
                <td>Entregado</td>
                @elseif($com->estado == 'Penalizado')
                <td>Penalizado</td>
                @else
                <td>{{$com->factura->estado}}</td>
                @endif

                @else
                <td>Sin factura</td>
                <td>Sin estado</td>
                @endif

                <td>{{$com->fecha_compromiso}}</td>
                <td>{{$com->fecha_devolucion}}</td>
                <td>{{$com->persona->name}}</td>
                <td>{{$com->persona->cedula}}</td>
                <td>{{$com->persona->telefono}}</td>
                <td>{{$com->persona->celular}}</td> 

                <td><a class="btnDetalleCompromiso" href="{{ url('compromiso/detalle/'.$com->id_compromiso) }}">Ver detalle </a><br>
                    @if($com->factura->estado == 'Pendiente' && $com->estado != 'Penalizado' )
                    @if(Auth::user()->rol2 == 'superusuario')
                    <a class="eliminarBtn" data-redirect="{{ url('/compromiso')}}" href="{{ url('/compromiso/eliminar/'.$com->id_compromiso) }}"> Anular</a><br>
                    @endif
                    <a href="{{url('/compromiso/entregar/'.$com->id_compromiso)}}">Entregar</a><br>
                   
                    <a href="{{url('/compromiso/penalizar/'.$com->id_compromiso)}}">Penalizar</a>
                    @elseif ($com->factura->estado == 'Pagada' && $com->estado != 'Devuelto')
                    <a href="{{url('/compromiso/devolver/'.$com->id_compromiso)}}">Devolución</a><br>
                    <a href="{{url('/compromiso/penalizar/'.$com->id_compromiso)}}">Penalizar</a>          
                    @endif
                </td>  
                <td>
                    <a href="{{url('/compromiso/abonar/'.$com->id_compromiso)}}">Abonos</a>
                    
                </td> 
                <td><label><input class="checkAjustar" data-url ="{{url('compromiso/ajustar/'.$com->id_compromiso)}} " type="checkbox" <?php echo ($com->ajustado) ? 'checked' : '' ?> value="1"></label></td>
            </tr>
            @endforeach
            @else

            @endif

        </tbody>

    </table>
</div>

<div class=" col-md-11 col-md-offset-0.5">
    <form method="post" action="{{url('compromiso/filtrar')}}">
        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email">Fecha de compromiso inicial:</label>
                <input required type="date" class="form-control" name="fechaInicio" id="">
            </div>

        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email">Fecha de compromiso final:</label>
                <input required type="date" class="form-control" name="fechaFin" id="">
            </div>

        </div>
        <button type="submit" class="btn btn-default pull-right">Filtrar</button>
    </form>

</div>


<hr>

<div class=" col-md-11 col-md-offset-0.5">
    <form method="post" action="{{url('compromiso/filtrar2')}}">
        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email">Número de identificación:</label>
                <input required type="number" class="form-control" name="cedula" id="">
            </div>

        </div>
        <div class="col-sm-6">
            <br>
            <button type="submit" class="btn btn-default ">Filtrar</button>

        </div>

    </form>

</div>

<hr>
<div class=" col-md-11 col-md-offset-0.5">
    <form method="post" action="{{url('compromiso/filtrar2')}}">
        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email">Número de factura:</label>
                <input required type="number" class="form-control" name="factura" id="">
            </div>

        </div>
        <div class="col-sm-6">
            <br>
            <button type="submit" class="btn btn-default ">Filtrar</button>

        </div>

    </form>

</div>




</div>

<!-- Modal -->
<div id="modalDetalleCompromiso" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Productos del compromiso</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
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

        });
        $('.btnDetalleCompromiso').click(function (e) {
            e.preventDefault();


            var url = $(this).attr('href');
            var urlredirect = $(this).attr('data-redirect');

            $.get(url).done(function (data) {
                if (data) {
                    $('#modalDetalleCompromiso .modal-body').html(data);
                    $('#modalDetalleCompromiso').modal('show');
                }
            });


        });
        $("input.checkAjustar").click(function () {
            var url = $(this).attr('data-url');
            if ($(this).is(':checked')) {
                $.get(url + '/a').done(function (data) {
                    if (data == 200) {
                        alert('El compromiso ha sido ajustado');
                    }
                });
            } else {
                $.get(url + '/d').done(function (data) {
                    if (data == 200) {
                        alert('El compromiso ha dejado de estar  ajustado');
                    }
                });
            }
        });
    });
</script>
@endsection
