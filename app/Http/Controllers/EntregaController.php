<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use App\Persona;
use App\Factura;
use App\Compromiso;
use App\CompromisoProducto;
use App\ConsecutivoFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EntregaController extends Controller
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
        $compromisos = Compromiso::all();
        return view('compromiso.index', compact('compromisos'));
    }
    public function create()
    {
        return view('compromiso.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $data)
    {
        $fecha_compromiso = $data->input('fecha_compromiso');
        $fecha_devolucion = $data->input('fecha_devolucion');
        $abono = $data->input('abono');
        $tipo_pago = $data->input('tipo_pago');
        $saldo = $data->input('saldo');
        $id_cliente = $data->input('id_cliente');
        $productos =  $data->input('productos');
        $banderaCompromiso = false;

        $compromiso = new Compromiso;

        $compromiso->fecha_compromiso = $fecha_compromiso;
        $compromiso->fecha_devolucion = $fecha_devolucion;
        $compromiso->cedula = $id_cliente;

        if ($compromiso->save()) {
            $banderaCompromiso = true;
        }
     
        if ($banderaCompromiso) {
            $id_compromiso = $compromiso->id_compromiso;
        
            //crear los compromisos_productos
    
            foreach ($productos as $producto) {
                $compromisoProducto = new CompromisoProducto;
                $compromisoProducto->id_producto = $producto[0];
                $compromisoProducto->ajustes = $producto[1];
                $compromisoProducto->id_compromiso = $id_compromiso;
                $compromisoProducto->save();
                //cambiar estado al producto
                $producto =  Producto::find($producto[0]);
                $producto->estado = 'Comprometido';
                $producto->save();
            }

              //crar la factura
        if ($banderaCompromiso) {
            $factura = new Factura;
            $factura->cedula = $id_cliente;
            $factura->valor = intval($abono) + intval($saldo);
            $factura->saldo = $saldo;
            $factura->abono = $abono;
            $factura->estado = 'Pendiente';
            $factura->metodo_pago = $tipo_pago;

            //obtener el consecutivo y aumentarlo mas 1 cuando se genere una factura nueva

            $consecutivoFactura = ConsecutivoFactura::find(1);

            
            if($factura->save()){
                $facturamodificar = Factura::find($factura->id_factura);
                $facturamodificar->numero_factura = $consecutivoFactura->numero;
                $consecutivoFactura->numero = $consecutivoFactura->numero + 1;
                $consecutivoFactura->save();
                $facturamodificar->save();

                $compromisoModificar = Compromiso::find($id_compromiso);
                $compromisoModificar->id_factura = $factura->id_factura;
                if($compromisoModificar->save()){
                    return url('/compromiso');
                }
                else{
                    return false;
                }
                

            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
        }

      
        
      
    }



  

    public function eliminar($id)
    {
        $compromiso = Compromiso::find($id);

        $productosCompromiso = CompromisoProducto::where('id_compromiso', $id)->get();

        if(count($productosCompromiso)>=1 && !is_null($productosCompromiso) && isset($productosCompromiso)){
            foreach($productosCompromiso as $producto){
                $productoModificar = Producto::find($producto->id_producto);
                $productoModificar->estado = 'Disponible';
                $productoModificar->save();

            }
        }
        $productosCompromiso = CompromisoProducto::where('id_compromiso', $id);
        $productosCompromiso->delete();
        $facturamodificar = Factura::find($compromiso->id_factura);

        if ($compromiso->delete()) {

            //buscar factura y borrarla 
            $facturamodificar->estado = 'Anulada';
            $facturamodificar->save(); 
            $facturamodificar->delete(); 

            $consecutivoFactura = ConsecutivoFactura::find(1);
            $consecutivoFactura->numero = $consecutivoFactura->numero - 1;
            $consecutivoFactura->save();

            echo true;
        }
    }


    public function detalle($id_compromiso){

        $productos = CompromisoProducto::where('id_compromiso', $id_compromiso)->get();
        return view('compromiso.detalle', compact('productos'));

    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
