<?php

namespace App\Http\Controllers;
use App\Producto;
use App\Proveedor;
use App\Persona;
use App\Garantia;
use App\Entrada;
use App\Salida;
use App\Factura;
use App\Compromiso;
use App\VentaProducto;
use App\CompromisoProducto;
use App\ConsecutivoFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DateTime;

class VentaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('venta.create');
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
        $cliente2 = Persona::where('cedula', $id_cliente)->first();


//traer el consecutivo de factura 

        $consecutivoFactura = ConsecutivoFactura::where('id_consecutivo', 1)->first();

//crear la factura 

        $nuevaFacturaVenta = new Factura();
        $nuevaFacturaVenta->numero_factura = $consecutivoFactura->numero;
        $numeroFacturaActual = $consecutivoFactura->numero;
        $nuevaFacturaVenta->cedula = $cliente2->cedula;
        $nuevaFacturaVenta->valor = $total;
        $nuevaFacturaVenta->fecha_pago = date('Y-m-d');
        $nuevaFacturaVenta->metodo_pago = $tipo_pago;
        $nuevaFacturaVenta->tipo = 'venta';
        $nuevaFacturaVenta->save();

        //aumentar en uno la factura 
        $consecutivoFactura->numero = $consecutivoFactura->numero + 1;
        $consecutivoFactura->save();

//crear la relacion venta_producto con el id de factura        


        if (count($productos)) {
            foreach ($productos as $producto) {
                $id = $producto[0];
                $nuevoVentaProducto = new VentaProducto();
                $nuevoVentaProducto->id_factura = $nuevaFacturaVenta->id_factura;
                $nuevoVentaProducto->id_producto = $id;
                $nuevoVentaProducto->save();
                $productoModificar = Producto::find($id);
                $productoModificar->estado = 'Vendido';
                $productoModificar->save();
            }
        }



        $this->crearReciboVenta($productos, $cliente2, $numeroFacturaActual, $total);
    }

    public function crearReciboVenta($productos, $cliente2, $numeroFacturaActual, $total) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('facturaVentaPlantilla.docx'));

        if (isset($cliente2->name)) {
            $my_template->setValue('nombreCliente', $cliente2->name);
        } else {
            $my_template->setValue('nombreCliente', '');
        }
        if (isset($cliente2->direccion)) {
            $my_template->setValue('direccionCliente', $cliente2->direccion);
        } else {
            $my_template->setValue('direccionCliente', '');
        }
        if (isset($cliente2->referencia_nombre)) {
            $my_template->setValue('referenciaCliente', $cliente2->referencia_nombre);
        } else {
            $my_template->setValue('referenciaCliente', '');
        }
        if (isset($cliente2->direccion)) {
            $my_template->setValue('direccionCliente', $cliente2->direccion);
        } else {
            $my_template->setValue('direccionCliente', '');
        }
        if (isset($cliente2->cedula)) {
            $my_template->setValue('cedulaCliente', $cliente2->cedula);
        } else {
            $my_template->setValue('cedulaCliente', '');
        }

        $my_template->setValue('fechaHoy', date('Y-m-d'));

        $my_template->setValue('total', $total);
        $my_template->setValue('facturaNumero', $numeroFacturaActual);
        

        if (isset($cliente2->telefono)) {
            $my_template->setValue('telefonoCliente', $cliente2->telefono);
        } else {
            $my_template->setValue('telefonoCliente', '');
        }
        if (isset($cliente2->celular)) {
            $my_template->setValue('celularCliente', $cliente2->celular);
        } else {
            $my_template->setValue('celularCliente', '');
        }        
        $conteoProductos = count($productos);

        if ($conteoProductos <= 15) {
            $j = 1;
            foreach ($productos as $prod) {
                $id = $prod[0];
                $buscadoProducto = Producto::find($id);
                $my_template->setValue('p' . $j, 'Venta: '. $buscadoProducto->nombre . ' - (' . $buscadoProducto->referencia . ')');
                $my_template->setValue('v' . $j, $buscadoProducto->valor_venta);
                $j++;
            }

            if ($j < 15) {
                for ($i = $j; $i <= 15; $i++) {
                    $my_template->setValue('p' . $i, '');
                    $my_template->setValue('v' . $i, '');
                }
            }
        }

        try {
            $my_template->saveAs(public_path('ReciboGeneradoVenta.docx'));
            echo 200;
        } catch (Exception $e) {
            //handle exception
        }
    }

  

   

   

}
