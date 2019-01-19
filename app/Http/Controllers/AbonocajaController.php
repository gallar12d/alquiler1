<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use App\Factura;
use App\Garantia;
use App\Entrada;
use App\Salida;
use App\Novedad;
use App\Base;
use App\Abonocaja;

use App\Custodia;
use App\CustodiaDetalle;
use Illuminate\Http\Request;

class AbonocajaController extends Controller {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function __construct() {

        $this->middleware('auth');
    }

    public function index() {
        
        return view('abonocaja.index');

        
    }

    public function ingresar(Request $data) {
        $valor_ingreso = $data->input('valor_ingresar');
        $concepto_ingreso = $data->input('concepto_ingresar');

        $nuevoRegistroCustodia = new Abonocaja();
        $nuevoRegistroCustodia->valor = $valor_ingreso;
        $nuevoRegistroCustodia->fecha = date('Y-m-d');
        $nuevoRegistroCustodia->concepto = $concepto_ingreso;
        $nuevoRegistroCustodia->save();

        return redirect('/abonocaja');
       
       
    }

}
