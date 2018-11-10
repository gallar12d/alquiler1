<?php

namespace App\Http\Controllers;

use App\Salida;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        
        return view('salida.create');
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $data)
    {
        $concepto = $data->input('concepto');
        $valor = $data->input('valor');
        $persona = $data->input('persona');
        $identificacion = $data->input('identificacion');
        $salida = new Salida;
        $salida->concepto = $concepto;
        $salida->valor = $valor;
        $salida->nombre_persona = $persona;
          $salida->identificacion = $identificacion;
        $salida->save();

        return redirect('/salida');

        
      
    }



}
