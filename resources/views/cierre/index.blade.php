@extends('layouts.app')



@section('content')



<div class="row">

    <div class ="col-md-10 col-md-offset-1">

    <h3>Generar cierre de caja</h3>

    <form class="formsubmit" method="post" action="{{ url('/cierre/generar') }}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">       

        <div class="form-group col-md-6">

            <label for="nombre">Fecha inicio *</label>

            <input required type="date" value="{{date('Y-m-d')}}" class="form-control" name ="fechaInicio">

        </div>

        <div class="form-group col-md-6" >

            <label for="nombre">Fecha fin *</label>

            <input required type="date" class="form-control" value="{{date('Y-m-d')}}" name ="fechaFin">

        </div>        

        

        <button type="submit" class="btn btn-default">Generar</button>

        </form>

    </div>



    <div class = "cierreGenerado col-md-10 col-md-offset-1">



    

    </div>



</div>



@endsection

@section('scripts')

<script type="text/javascript">

    $(document).on('submit', 'form.formsubmit', function(e){
    e.preventDefault();


    data = $(this).serialize();

    url = $(this).attr('action');

    $.post(url, data).done(function(data){

        if(data){
            

            $('div.cierreGenerado').html(data);

            totalFacturasNuevas();
            totalAbonos();
            totalGarantias();
            totalRecargos();
            totalVentas();
            totalPrestamos();
            totalDaños();
            totalBases();
            totalAbonosCaja();
            totalRestas();

            // realizar cálculos
            var elementossuma = $('.sumas');
            var sumas = 0;
                if(elementossuma.length){
                    
                    $.each(elementossuma, function(k, v){
                        var valor = $(this).text();                   

                        valor =  parseInt(valor);
                        sumas = sumas + valor;
                    })
                }

                var elementosresta = $('.restas');

                var resta = 0;

                if(elementosresta.length){

                    $.each(elementosresta, function(k, v){

                        var valor = $(this).text();

                        valor =  parseInt(valor.substring(2));

                        resta = resta + valor;



                    })

                    

                }



                var total = sumas - resta;

                
                total2 = total;
               



                $('#total').text(total+' Pesos');

                

                

                //realizar cálculos de efectivo y tarjeta

             

            // realizar cálculos

            var elementossuma = $('.tarjeta');

            var sumas2 = 0;

                if(elementossuma.length){

                    $.each(elementossuma, function(k, v){

                        var valor = $(this).text();

                        valor =  parseInt(valor);

                        sumas2 = sumas2 + valor;



                    });

                    

                }


                var elementosbase = $('.base');

            var sumasBase = 0;

                if(elementosbase.length){

                    $.each(elementosbase, function(k, v){

                        var valor = $(this).text();

                        valor =  parseInt(valor);

                        sumasBase = sumasBase + valor;



                    });

                    

                }

                

                $('#totalventas').text(total2 - sumasBase + ' Pesos');


                $('#totalTarjeta').text(sumas2+' Pesos');

                 $('#totalEfectivo').text(total - sumas2+' Pesos');

        }





    })
})

function totalFacturasNuevas(){
    var elementos = $('.facturaNueva');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalFacturasNuevas').text(suma);
}
function totalAbonos(){
    var elementos = $('.abono');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalAbonos').text(suma);
}
function totalGarantias(){
    var elementos = $('.garantia');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalGarantias').text(suma);
}
function totalRecargos(){
    var elementos = $('.recargo');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalRecargos').text(suma);
}
function totalVentas(){
    var elementos = $('.venta');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalVentas').text(suma);
}
function totalPrestamos(){
    var elementos = $('.prestamo');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalPrestamos').text(suma);
}
function totalDaños(){
    var elementos = $('.daño');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalDaños').text(suma);
}
function totalBases(){
    var elementos = $('.basesuma');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalBases').text(suma);
}
function totalAbonosCaja(){
    var elementos = $('.abonoCaja');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor);
        });
    }
   
    $('#totalAbonosCaja').text(suma);
}
function totalRestas(){
    var elementos = $('.resta');
    var suma = 0;
    if(elementos.length){
        $.each(elementos, function(k,v){
            var valor = $(v).text();
            suma = parseInt(suma) + parseInt(valor.substring(2));
        });
    }
   
    $('#totalRestas').text(suma);
}




</script>

@endsection('scripts')