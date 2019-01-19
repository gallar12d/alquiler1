<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use App\Factura;
use App\Garantia;
use App\Entrada;
use App\Salida;
use App\Novedad;
use App\Custodia;
use App\CustodiaDetalle;
use Illuminate\Http\Request;

class CustodiaController extends Controller {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function __construct() {

        $this->middleware('auth');
    }

    public function index() {
        $custodia = Custodia::find(1);
        return view('custodia.index', compact('custodia'));

        return view('custodia.index');
    }

    public function ingresar(Request $data) {
        $valor_ingreso = $data->input('valor_ingresar');

        $nuevoRegistroCustodia = new CustodiaDetalle();
        $nuevoRegistroCustodia->valor = $valor_ingreso;
        $nuevoRegistroCustodia->fecha = date('Y-m-d');
        $nuevoRegistroCustodia->tipo = 'Ingreso';
        $nuevoRegistroCustodia->save();

        //aumentar la custodia el valor

        $custodia = Custodia::find(1);
        $custodia->valor = $custodia->valor + $valor_ingreso;
        $custodia->save();
        $custodia = Custodia::find(1);
        return redirect('/custodia');
        
       
    }

    public function debitar(Request $data) {
        $valor_debito = $data->input('valor_debitar');

        // verificar si lo que entra es menor de lo que hay
        $custodia2 = Custodia::find(1);
        $custodiaValor = $custodia2->valor;

        if ($valor_debito <= $custodiaValor) {
            $nuevoRegistroCustodia = new CustodiaDetalle();
            $nuevoRegistroCustodia->valor = $valor_debito;
            $nuevoRegistroCustodia->fecha = date('Y-m-d');
            $nuevoRegistroCustodia->tipo = 'Debito';
            $nuevoRegistroCustodia->save();
            $custodia3 = Custodia::find(1);
            $custodia3->valor = $custodia3->valor - $valor_debito;
            $custodia3->save();
            $custodia = Custodia::find(1);
            return redirect('/custodia');
        } else {
            echo'El valor a debitar no puede ser mayor al valor de la custodia!';
        }




        //aumentar la custodia el valor
    }

    public function generar(Request $data) {
        $fechaInicio = $data->input('fechaInicio');
        $fechaFin = $data->input('fechaFin');

        $registros = CustodiaDetalle::where('fecha', '>=', $fechaInicio)
                        ->where('fecha', '<=', $fechaFin)->get();

        if (count($registros)) {

            echo '  <table class="table">
    <thead>
      <tr>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Valor</th>
      </tr>
    </thead>
    <tbody>';


            foreach ($registros as $registro) {
                echo '<tr>
        <td>' . $registro->fecha . '</td>
        <td>' . $registro->tipo . '</td>
        <td>' . $registro->valor . '</td>
      </tr>';
            }

            echo'  </tbody>
  </table>';
        }
        else{
            echo 'No se encontraron movimientos para este rango de fechas';
        }
    }

    /**



      /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response


      /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Producto  $producto

     * @return \Illuminate\Http\Response

     */
}
