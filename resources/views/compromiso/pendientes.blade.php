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
                    
                </td>      
            </tr>
            @endforeach
            @else

            @endif

        </tbody>

    </table>
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
        $("input.checkAjustar").click( function(){
            var url = $(this).attr('data-url');
            if( $(this).is(':checked') ){
                  $.get(url + '/a').done(function(data){
                      if(data == 200){
                          alert('El compromiso ha sido ajustado');
                      }
                  });
            } 
            else{
                $.get(url + '/d').done(function(data){
                      if(data == 200){
                          alert('El compromiso ha dejado de estar  ajustado');
                      }
                  });
            }
         });
    });
</script>
@endsection
