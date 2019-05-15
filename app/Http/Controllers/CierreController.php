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
use App\Abono;

use App\PrestamoProducto;
use Illuminate\Http\Request;

class CierreController extends Controller {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function __construct() {

        $this->middleware('auth');
    }

    public function index() {

        return view('cierre.index');
    }

    public function generar(Request $data) {
        $fechaInicio = $data->input('fechaInicio');

        $fechaFin = $data->input('fechaFin');

        $facturasAbonos = Factura::whereNotNull('metodo_pago')
                        ->where('created_at', '>=', $fechaInicio)
                        ->where('created_at', '<=', $fechaFin)->whereNull('tipo')->orderBy('numero_factura', 'asc')->get();
                       
        $totalFacturasAbonos = Factura::whereNotNull('metodo_pago')->where('created_at', '>=', $fechaInicio)->where('created_at', '<=', $fechaFin)->whereNull('tipo')->sum('abono');
        $facturasSaldos = Factura::whereNotNull('metodo_pago_saldo')
                        ->where('created_at', '>=', $fechaInicio)
                        ->where('created_at', '<=', $fechaFin)                
                       ->whereNull('tipo')->get();

        $garantias = Garantia::where('created_at', '>=', $fechaInicio)
                ->where('created_at', '<=', $fechaFin)->where('estado', 1)
                ->get();

        $recargos = Entrada::where('tipo', 'Recargo')
                        ->where('created_at', '>=', $fechaInicio)
                        ->where('created_at', '<=', $fechaFin)->get();

        $danios = Entrada::where('tipo', 'Daños')
                        ->where('created_at', '>=', $fechaInicio)
                        ->where('created_at', '<=', $fechaFin)->get();

        $salidas = Salida::where('created_at', '>=', $fechaInicio)
                        ->where('created_at', '<=', $fechaFin)->get();

        $novedades = Novedad::where('created_at', '>=', $fechaInicio)
                        ->where('created_at', '<=', $fechaFin)->get();

        $ventas = Factura::where('fecha_pago', '>=', $fechaInicio)
                        ->where('fecha_pago', '<=', $fechaFin)
                        ->where('tipo', 'venta')->get();
        
         $prestamos = PrestamoProducto::where('fecha', '>=', $fechaInicio)
                        ->where('fecha', '<=', $fechaFin)->get();
         
         $bases = Base::where('fecha', '>=', $fechaInicio)
                        ->where('fecha', '<=', $fechaFin)->get();
          $abonoscaja = Abonocaja::where('fecha', '>=', $fechaInicio)
                        ->where('fecha', '<=', $fechaFin)->get();
          
          $abonosCompromiso = Abono::where('fecha', '>=', $fechaInicio)
                        ->where('fecha', '<=', $fechaFin)->get();









        return view('cierre.generado', compact('totalFacturasAbonos','abonosCompromiso', 'abonoscaja','bases', 'prestamos', 'ventas', 'novedades', 'facturasAbonos', 'facturasSaldos', 'garantias', 'recargos', 'danios', 'salidas'));
    }

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function crear(Request $data) {





        if (Producto::create($data->all())) {

            echo true;
        }
    }

    public function postEditar(Request $data) {

        if (Producto::where('id', $data->id)
                        ->update($data->except(['_token']))) {

            echo true;
        }
    }

    public function buscarproducto($id) {



        $productos = Producto::where('id', $id)
                        ->orWhere('nombre', 'like', $id . '%')
                        ->orWhere('referencia', 'like', $id . '%')->get();





        return $productos;
    }

    public function editar($id) {



        $producto = Producto::find($id);

        $proveedores = Proveedor::all();



        return view('inventario.edit', compact('producto', 'proveedores'));
    }

    public function eliminar($id) {



        $producto = Producto::find($id);



        if ($producto->delete()) {



            echo true;
        }
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {

        //
    }

    /**

     * Display the specified resource.

     *

     * @param  \App\Producto  $producto

     * @return \Illuminate\Http\Response

     */
    public function show(Producto $producto) {

        //
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Producto  $producto

     * @return \Illuminate\Http\Response

     */
    public function edit(Producto $producto) {

        //
    }

    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Producto  $producto

     * @return \Illuminate\Http\Response

     */
    public function update(Request $request, Producto $producto) {

        //
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Producto  $producto

     * @return \Illuminate\Http\Response

     */
    public function destroy(Producto $producto) {

        //
    }

}
