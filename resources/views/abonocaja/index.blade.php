@extends('layouts.app')



@section('content')



<div class="row">
    
    

    <div class ="col-md-10 col-md-offset-1">    
       

        <form class="col-md-12" method="POST" action="{{url('abonocaja/ingresar')}}">
            <div class="form-group col-md-6">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <label for="nombre">Ingresar valor del abono</label>
                <input required type="number"  class="form-control" name ="valor_ingresar">

            </div>
            <div class="form-group col-md-6">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <label for="nombre">Ingresar concepto del abono</label>
                <input  type="text"  class="form-control" name ="concepto_ingresar">

            </div>
            <div class="form-group col-md-4">

                <br>

                <button type="submit" class="btn btn-default">Ingresar abono</button>

            </div>


        </form>
      
        
       

    </div>






</div>



@endsection

@section('scripts')

<script type="text/javascript">

    $(document).on('submit', 'form.formsubmit', function (e) {

        e.preventDefault();



        data = $(this).serialize();

        url = $(this).attr('action');



        $.post(url, data).done(function (data) {

            if (data) {



                $('div.cierreGenerado').html(data);

                // realizar cálculos

                var elementossuma = $('.sumas');

                var sumas = 0;

                if (elementossuma.length) {

                    $.each(elementossuma, function (k, v) {

                        var valor = $(this).text();

                        valor = parseInt(valor);

                        sumas = sumas + valor;



                    })



                }

                var elementosresta = $('.restas');

                var resta = 0;

                if (elementosresta.length) {

                    $.each(elementosresta, function (k, v) {

                        var valor = $(this).text();

                        valor = parseInt(valor.substring(2));

                        resta = resta + valor;



                    })



                }



                var total = sumas - resta;







                $('#total').text(total + ' Pesos');





                //realizar cálculos de efectivo y tarjeta



                // realizar cálculos

                var elementossuma = $('.tarjeta');

                var sumas2 = 0;

                if (elementossuma.length) {

                    $.each(elementossuma, function (k, v) {

                        var valor = $(this).text();

                        valor = parseInt(valor);

                        sumas2 = sumas2 + valor;



                    });



                }

                $('#totalTarjeta').text(sumas2 + ' Pesos');

                $('#totalEfectivo').text(total - sumas2 + ' Pesos');

            }





        })





    })

</script>

@endsection('scripts')