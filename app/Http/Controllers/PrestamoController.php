<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use App\Persona;
use App\Garantia;
use App\Entrada;
use App\Salida;
use App\Factura;
use App\Sede;
use App\Compromiso;
use App\VentaProducto;
use App\PrestamoProducto;
use App\CompromisoProducto;
use App\ConsecutivoFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DateTime;

class PrestamoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $sedes = Sede::all();
        return view('prestamo.create', compact('sedes'));
    }

    public function postFiltrar(Request $data) {
        $fechaInicial = $data->input('fechaInicio');
        $fechaFin = $data->input('fechaFin');
        $compromisos = Compromiso::where('fecha_compromiso', '>=', $fechaInicial)->where('fecha_compromiso', '<=', $fechaFin)->get();
        return view('compromiso.index', compact('compromisos'));
    }

    public function create() {
        return view('compromiso.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreate(Request $data) {


        $tipo_pago = $data->input('tipo_pago');
        $total = $data->input('total');
        $id_cliente = $data->input('id_cliente');
        $productos = $data->input('productos');
        $sede = $data->input('sede');


        if (count($productos)) {
            foreach ($productos as $producto) {
                $id = $producto[0];
                $nuevoVentaProducto = new PrestamoProducto();
                $nuevoVentaProducto->id_sede = $sede;
                $nuevoVentaProducto->id_producto = $id;
                $nuevoVentaProducto->fecha = date('Y-m-d');
                $productoModificar = Producto::find($id);
                $productoModificar->estado = 'Prestado';
                $nuevoVentaProducto->valor = $productoModificar->valor / 2;
                $nuevoVentaProducto->save();
                $productoModificar->save();
            }
        }

        echo 200;
    }

   public function getPendientes(){
       
       $fechaRestada = date("Y-m-d",strtotime(date('Y-m-d')." - 9 days")); 
       
       $pendientes = PrestamoProducto::where('fecha', '<=', $fechaRestada)
               ->where('estado', 'Prestado')->get();
       
       
       
         return view('prestamo.pendientes', compact('pendientes'));
   }

}
