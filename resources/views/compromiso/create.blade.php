@extends('layouts.app')

@section('content')

<div class="col-md-10 col-md-offset-1">

    <form class="formsubmit" method="post" action="{{ url('/persona/crear') }}">

        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group col-md-6 col-md-offset-0" >

            <label for="nombre">Fecha compromiso *</label>

            <input id="fecha_compromiso" required type="date" class="form-control" name ="fecha_compromiso">

        </div>
        <div class="form-group col-md-6 ">

            <label for="nombre">Fecha actual</label>

            <input   type="text" value="{{date('d-m-Y')}}" class="form-control"   disabled>

        </div> 

        <div class="form-group">            

            <div class="row">

                <div class= "col-md-6">

                    <label for="nombre">Cliente </label>

                    <div class="input-group ">

                        <input id="buscarCliente" data-url="{{url('/persona/buscarcliente')}}" type="text" class="form-control"  placeholder="Buscar cliente" >

                        <input id="idCliente" type="hidden"   type="number" class="form-control" name ="id_cliente">

                        <span class="input-group-addon">

                            <a id="btnBuscarCliente" >

                                <span class="glyphicon glyphicon-search"></span>

                            </a>  

                        </span>

                    </div>

                </div>

            </div>

            <table id="tableCliente" class="table table-striped">

                <thead>

                    <tr>

                        <th>No identificación</th>

                        <th>Nombres</th>
                        <th>Descuento</th>




                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td id="clienteCedula">No</td>

                        <td id="clienteNombres">No</td>   
                        <td id="clienteDescuento">                            
                            <div  id="textoDescuento">
                                No
                            </div>
                            <div id="checkboxDescuento">
                                <label><input id="checkboxDesc" checked type="checkbox" value="">No aplica</label>
                            </div>
                        </td>  




                    </tr>



                </tbody>

            </table>

        </div>        

        <div class="form-group">            

            <div class="row">

                <div class= "col-md-6">

                    <label for="nombre">Producto </label>

                    <div class="input-group ">

                        <input id="buscarProducto"  data-url ="{{url('/inventario/buscarproducto')}}" type="text" class="form-control"  placeholder="Buscar producto" >

                        <span class="input-group-addon">

                            <a id="btnBuscarProducto">

                                <span class="glyphicon glyphicon-search"></span>

                            </a>  

                        </span>

                    </div>

                </div>

            </div>

            <table data-url = "{{url('/compromiso/crear')}}" id="tableProductos" class="table table-striped">

                <thead>

                    <tr >    



                        <th>Nombre</th>

                        <th>Talla</th>

                        <th>Color</th>

                        <th>Referencia</th>

                        <th>Estado</th>

                        <th>Fecha devolución</th>

                        <th >Valor</th>

                        <th>Linea</th>

                        <th>Ajustes</th>

                        <th>Acciones</th>                       

                    </tr>

                </thead>

                <tbody>

                    <tr id="fila1">                        

                        <td>No</td>

                        <td>No</td>

                        <td>No</td>  

                        <td>No</td>

                        <td>No</td>  

                        <td>No</td>

                        <td>No</td> 

                        <td>No</td>  

                        <td>No</td>                                

                    </tr>                    

                </tbody>

                <tfoot>

                    <tr>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td data-total ='' id="totalvalor">Total: </td>



                    </tr>

                </tfoot>

            </table>

        </div>        

        <div class="form-group col-md-6 ">

            <label for="nombre">Fecha devolución *</label>

            <input id="fecha_devolucion" required type="date" class="form-control"  name ="fecha_devolucion">

        </div>    

        <div class="form-group col-md-6 ">

            <label for="nombre">Abono *</label>

            <input id="abono"  required type="number" class="form-control"  name ="abono">

        </div> 

        <div class="form-group col-md-6 ">

            <label for="nombre">Saldo</label>

            <input id="saldo" required type="number" class="form-control"  name ="saldo" disabled>

        </div> 

        <div class="form-group col-md-6">

            <label for="nombre">Tipo de pago</label>

            <select id="tipo_pago" class="form-control" name = 'tipo_pago' >

                <option value="Efectivo">Efectivo</option>          

                <option value="Tarjeta">Tarjeta</option>           

            </select>

        </div> 


        <div class="col-md-12">


            <a id="btnCrearcompromiso" class="  col-md-3 btn btn-default ">Crear</a>
            <!--<a id="btnDescargar" target="_blank" href="{{ asset('public/ReciboGenerado.docx') }}"  class="pull-right btn btn-default">Descargar recibo</a>-->
            <a id="btnDescargar" target="_blank" href="{{ asset('/ReciboGenerado.docx') }}"  class="pull-right btn btn-default">Descargar recibo</a>



            <br>
            <br>
            <br>



        </div> 

    </form>

</div>

<br>
<br>
<br>

<!-- Modal agregar productos-->

<div id="modalProductos" class="modal fade" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Producto</h4>

            </div>

            <div class="modal-body">

                <table id="tableProductosmodal" class="table table-striped">

                    <thead>

                        <tr>

                            <th></th>

                            <th>Nombre</th>

                            <th>Talla</th>

                            <th>Color</th>

                            <th>Referencia</th>

                            <th>Estado</th>

                            <th>Valor</th>

                            <th>Linea</th>      

                            <th>Fecha devolucion</th>  



                        </tr>

                    </thead>

                    <tbody>





                    </tbody>



                </table>



            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                <button type="button" class=" btnAgregarProductoLista btn btn-default" >Agregar</button>

            </div>

        </div>



    </div>

</div>



@endsection

@section('scripts')

<script type="text/javascript">

    $(document).ready(function () {

        $('a#btnDescargar').hide();

        $(document).on('change', 'input#checkboxDesc', function () {
            valortotal();

            $('input#abono').trigger('keyup');
        });
        function eliminarfila(id) {

            $('#tableProductos tbody').find('#tr' + productoNuevo.id).remove();

        }

        var productoNuevo;
        var nuevosProductos;



        //calcular saldo 

        $('#btnBuscarCliente').on('click', function () {

            var valuecliente = $('#buscarCliente').val();

            if (valuecliente) {

                var url = $('#buscarCliente').attr('data-url');

                url = url + '/' + valuecliente;

                $.get(url).done(function (data) {
                    if (data.id) {
                        $('td#clienteCedula').text(data.cedula);
                        $('td#clienteNombres').text(data.name);
                        $('td#clienteDescuento div#textoDescuento').text(data.descuento + ' %');
                        $('td#clienteDescuento div#textoDescuento').attr('data-descuento', data.descuento);
                        $('input#idCliente').val(data.cedula);

                    } else {

                        alert('No se ha encontrado al cliente');

                    }

                });



            }

        })



        $('#btnBuscarProducto').on('click', function () {



            var fecha_compromiso = $('input#fecha_compromiso').val();

            if (!fecha_compromiso)

            {

                alert('Digite la fecha de compromiso');

                return 0;

            }



            var oneDay = 24 * 60 * 60 * 1000;

            var diff = 0;



            var valueproduct = $('#buscarProducto').val();

            if (valueproduct) {

                var url = $('#buscarProducto').attr('data-url');

                url = url + '/' + valueproduct;

                $.get(url).done(function (data) {



                    if (data.length == 0) {

                        alert('No se ha encontrado el producto!')

                        return 0;



                    } else if (data.length >= 1) {

                        nuevosProductos = data;

                        $('#tableProductosmodal tbody').empty();

                        $.each(data, function (k, v) {

                            data = v;

                            var textdisabled = "";

                            if (data.estado_producto == 'Lavanderia' || data.estado_producto == 'Prestado') {

                                textdisabled = 'disabled';

                            }

                            if (data.fecha_devolucion != null) {





                                //sacar la diferencia mayor de 4  entre la fecha del produco y la fecha de compromso

                                var date1 = new Date(fecha_compromiso);

                                var date2 = new Date(data.fecha_devolucion);



                                if (date1 > date2) {



                                    diff = Math.round(Math.abs((date2.getTime() - date1.getTime()) / (oneDay)));





                                    if (diff <= 3) {

                                       

                                    }

                                }
                                else if(date1 < date2){
                                     textdisabled = 'disabled';
                                }

                            } else {

                                data.fecha_devolucion = 'No fecha';

                            }







                            var text = '<tr><td data-idproducto ="' + data.idpro + '"><input class="addproduct" type="checkbox" ' + textdisabled + ' value="' + data.idpro + '"></td><td>' + data.nombre + '</td><td>' + data.talla + '</td><td>' + data.color + '</td><td>' + data.referencia + '</td><td>' + data.estado_producto + '</td><td>' + data.valor + '</td><td>' + data.linea + '</td><td>' + data.fecha_devolucion + '</td></tr>'

                             if (data.estado_producto != 'Vendido') {
                                 $('#tableProductosmodal tbody').append(text);
                            }

                          

                        })

                        $('#modalProductos').modal('show');



                    }





                })



            }

        })



        $('#modalProductos .btnAgregarProductoLista').on('click', function () {



            var selectedProducts = $('#modalProductos .addproduct:checked');



            if (selectedProducts.length) {



                $.each(nuevosProductos, function (k, producto) {



                    $.each(selectedProducts, function (key, productoSel) {



                        if (productoSel.value == producto.idpro) {

                            console.log('llega1');

                            productoNuevo = producto;

                            var existe = $('#tableProductos tbody').find('#tr' + productoNuevo.idpro);

                            if (!existe.length) {

                                var fecha_dev = 'Sin fecha';



                                if (productoNuevo.fecha_devolucion) {

                                    fecha_dev = productoNuevo.fecha_devolucion;

                                }

                                $('#fila1').remove();

                                console.log('llega2');

                                var html = "<tr class='productoagregado' data-id='" + productoNuevo.idpro + "' id='tr" + productoNuevo.idpro + "'> <td>" + productoNuevo.nombre + "</td>  <td>" + productoNuevo.talla + "</td><td>" + productoNuevo.color + "</td> <td>" + productoNuevo.referencia + "</td><td>" + productoNuevo.estado_producto + "</td><td>" + fecha_dev + "</td><td class='valor'>" + productoNuevo.valor + "</td><td>" + productoNuevo.linea + "</td><td><textarea ></textarea></td><td><a onclick='eliminarfila(" + productoNuevo.idpro + ")'>Eliminar</a></td</tr>";

                                $('#tableProductos tbody').append(html);



                            }

                        }

                    })



                })



            }



            $('#modalProductos ').modal('hide');

            valortotal();

        })



        $('input#abono').on('keyup', function (e) {

            var dato = e.target.value;

            var total = $('#totalvalor').attr('data-total');

            var saldo = parseInt(total) - parseInt(dato);

            $("input#saldo").val(saldo);


        })





        //crear el boton de crear compromiso





        $('#btnCrearcompromiso').on('click', function () {



            


            var fecha_compromiso = $('input#fecha_compromiso').val();

            var id_cliente = $('input#idCliente').val();

            var fecha_devolucion = $('input#fecha_devolucion').val();

            var abono = $('input#abono').val();

            var saldo = $('input#saldo').val();





            if (saldo < 0) {

                alert('El abono no puede ser mayor que el valor total');

                return 0;

            }

            var tipo_pago = $('select#tipo_pago option:selected').val()



            var fila1 = $('#tableProductos').find('tr#fila1');

            if (!fecha_compromiso) {

                alert('Por favor seleccione la fecha de compromiso');

                return 0;

            }

            if (!id_cliente) {

                alert('Por favor seleccione un cliente');

                return 0;

            }

            if (!fecha_devolucion) {

                alert('Por favor seleccione una fecha de devolución');

                return 0;

            }



            if (fila1.length) {

                alert('Por favor ingrese un producto');

                return 0;

            }



            if (!abono) {

                alert('Por favor ingrese un abono');

                return 0;

            }
            
            if( (new Date(fecha_compromiso).getTime() > new Date(fecha_devolucion).getTime()))
                {
                    alert('La fecha de compromiso debe ser menor o igual a la fecha de devolucion');

                    return 0;
                }


           if(!confirm('Está seguro que toda la iformación está correcta?')){
            return 0;
           }  
                




            var productos = $('#tableProductos').find('tr.productoagregado');

            var url = $('#tableProductos').attr('data-url')

            var token = $('input#token').val();



            if (productos.length) {

                var productosarray = [];

                $.each(productos, function (k, v) {

                    var id = $(this).attr('data-id');

                    var ajuste = $(this).find('textarea').val();



                    if (id) {

                        productosarray.push([id, ajuste]);



                    }



                });



                $('#btnCrearcompromiso').hide();

                var descuento = 0;

                if ($('input#checkboxDesc').is(':checked')) {
                    descuento = 0;
                } else {
                    descuento = 1;
                }

                $.post(url, {'tipo_pago': tipo_pago, 'saldo': saldo, 'abono': abono, '_token': token, 'fecha_compromiso': fecha_compromiso, 'id_cliente': id_cliente, 'fecha_devolucion': fecha_devolucion, 'productos': productosarray, 'descuento': descuento}).done(function (data) {



                    if (data) {



                        if (data == 500) {

                            alert('Por favor cambiar fecha de compromisos');

                            $('#btnCrearcompromiso').show();

                            return 0;

                        } else if (data == 200) {

                            alert('Se ha creado el compromiso correctamente, puede descargar el recibo en el boton de abajo');

                            $('a#btnDescargar').show();



                        }

                    } else {



                        alert('Algo salió mal, intente nuevamente');

//                        location.reload();



                    }



                });



            } else {

                alert('Por favor ingrese un producto');

                return 0;

            }





        });





    });



    function eliminarfila(id) {

        $('#tableProductos tbody').find('#tr' + id).remove();

        valortotal();

    }



    function valortotal() {

        var suma = 0;

        var elementos = $('td.valor');

        if (elementos.length) {



            $.each(elementos, function (k, v) {

                console.log($(this).text())

                suma = suma + parseInt($(this).text());

            })





        }

        var textviejo = 'Total:'




        if (!$('input#checkboxDesc').is(':checked')) {
            var descuento = $('#textoDescuento').attr('data-descuento');


            if (descuento != 0 || descuento != '0') {
                var restarDescuento = (parseInt(suma) * parseInt(descuento)) / 100;


                suma = suma - restarDescuento;

            }

        }
        textviejo = textviejo + ' ' + suma;


        $('#totalvalor').text(textviejo)

        $('#totalvalor').attr('data-total', suma)

    }









</script>

@endsection

