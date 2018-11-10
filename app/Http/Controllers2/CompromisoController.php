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
use App\CompromisoProducto;
use App\ConsecutivoFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DateTime;

class CompromisoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $compromisos = Compromiso::all();
        return view('compromiso.index', compact('compromisos'));
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
    public function crear(Request $data) {
        $fecha_compromiso = $data->input('fecha_compromiso');
        $fecha_devolucion = $data->input('fecha_devolucion');
        $abono = $data->input('abono');
        $tipo_pago = $data->input('tipo_pago');
        $saldo = $data->input('saldo');
        $id_cliente = $data->input('id_cliente');
        $productos = $data->input('productos');
        $banderaCompromiso = false;
        $cliente2 = Persona::where('cedula', $id_cliente)->first();

        //verificar las fechas de cada producto si es que estan comprometidos y ver si se puede 

        foreach ($productos as $producto) {
            $id = $producto[0];

            $tieneCompromisos = CompromisoProducto::where('id_producto', $id)->get();
            if (count($tieneCompromisos)) {

                foreach ($tieneCompromisos as $compro) {
                    $compromiso = Compromiso::find($compro->id_compromiso);
                    $fechaDevol = $compromiso->fecha_devolucion;
                    //comparar cada fecha de devolución con la fecha nueva de compromiso tiene que estar con mas de 4 dias
                    if ($fechaDevol >= $fecha_compromiso) {
                        return 500;
                    } else if ($fechaDevol < $fecha_compromiso) {

                        $date1 = new DateTime($fechaDevol);
                        $date2 = new DateTime($fecha_compromiso);
                        $interval = $date1->diff($date2);
                        $interval = intval($interval->d);
                        if ($interval <= 3) {

                            return 500;
                        }
                    }
                }
            }
        }



        $compromiso = new Compromiso;
        $compromiso->fecha_compromiso = $fecha_compromiso;
        $compromiso->fecha_devolucion = $fecha_devolucion;
        $compromiso->estado = 'Creado';
        $compromiso->cedula = $id_cliente;

        if ($compromiso->save()) {
            $banderaCompromiso = true;
        }

        if ($banderaCompromiso) {
            $id_compromiso = $compromiso->id_compromiso;

            //crear los compromisos_productos
            $concepto = 'Compromiso de productos: ';

            foreach ($productos as $producto) {
                $compromisoProducto = new CompromisoProducto;
                $compromisoProducto->id_producto = $producto[0];
                $compromisoProducto->ajustes = $producto[1];
                $compromisoProducto->id_compromiso = $id_compromiso;
                $compromisoProducto->save();
                //cambiar estado al producto
                $producto = Producto::find($producto[0]);
                $concepto = $concepto . $producto->nombre . ', ';
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
                if ($factura->save()) {
                    $facturamodificar = Factura::find($factura->id_factura);
                    $facturamodificar->numero_factura = $consecutivoFactura->numero;
                    $numeroFactura = $consecutivoFactura->numero;
                    $consecutivoFactura->numero = $consecutivoFactura->numero + 1;
                    $consecutivoFactura->save();
                    $facturamodificar->save();
                    $compromisoModificar = Compromiso::find($id_compromiso);
                    $compromisoModificar->id_factura = $factura->id_factura;
                    if ($compromisoModificar->save()) {

                        $url = url('/compromiso');
                        $factura = $numeroFactura;
                        $cliente = $id_cliente;
                        $valorTotal = intval($abono) + intval($saldo);
                        $valorAbono = intval($abono);
                        $valorSaldo = intval($saldo);
                        $fecha = date("Y-m-d");
                        //datos para word
                        $nombreCliente = '';
                        $direccionCliente = '';
                        $referenciaCliente = '';
                        $cedulaCliente = '';
                        $fechaHoy = date('Y-m-d');
                        $fechaComp = '';
                        $abono2 = '';
                        $saldo2 = '';
                        $total2 = '';
                        $fechaDev = '';
                        $facturaNumero2 = '';
                        $celularCliente = '';
                        $telefonoCliente = '';

                        $productosRecibo = array();

                        if (isset($productos)) {
                            $productosRecibo = $productos;
                        }
                        if (isset($cliente2->name)) {
                            $nombreCliente = $cliente2->name;
                        }
                        if (isset($cliente2->celular)) {
                            $celularCliente = $cliente2->celular;
                        }
                        if (isset($cliente2->telefono)) {
                            $telefonoCliente = $cliente2->telefono;
                        }
                        if (isset($cliente2->direccion)) {
                            $direccionCliente = $cliente2->direccion;
                        }
                        if (isset($cliente2->direccion)) {
                            $direccionCliente = $cliente2->direccion;
                        }
                        if (isset($cliente2->referencia_nombre)) {
                            $referenciaCliente = $cliente2->referencia_nombre;
                        }
                        if (isset($cliente2->cedula)) {
                            $cedulaCliente = $cliente2->cedula;
                        }

                        if (isset($fecha_compromiso)) {
                            $fechaComp = $fecha_compromiso;
                        }
                        if (isset($fecha_devolucion)) {
                            $fechaDev = $fecha_devolucion;
                        }
                        if (isset($abono)) {
                            $abono2 = $abono;
                        }
                        if (isset($saldo)) {
                            $saldo2 = $saldo;
                        }
                        if (isset($numeroFactura)) {
                            $facturaNumero2 = $numeroFactura;
                        }
                        if (isset($saldo) && isset($abono)) {
                            $total2 = intval($abono) + intval($saldo);
                        }

                        $this->crearRecibo2($nombreCliente, $direccionCliente, $referenciaCliente, $cedulaCliente, $fechaHoy, $fechaComp, $abono2, $saldo2, $total2, $fechaDev, $facturaNumero2, $telefonoCliente, $celularCliente, $productosRecibo);
//                        $this->crearRecibo($url, $factura, $cliente, $valorTotal, $valorAbono, $valorSaldo, $fecha, $concepto);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function crearRecibo($url, $factura, $cliente, $valorTotal, $valorAbono, $valorSaldo, $fecha, $concepto) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('Factura No: ' . $factura);
        $text = $section->addText('Valor factura: ' . $valorTotal);
        $text = $section->addText('Valor abono: ' . $valorAbono);
        $text = $section->addText('Valor saldo: ' . $valorSaldo);
        $text = $section->addText('Concepto: ' . $concepto);
        $text = $section->addText('Cliente: ' . $cliente);
        $text = $section->addText('Fecha: ' . $fecha);
//        $section->addImage("./images/Krunal.jpg");
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        if (file_exists(public_path('Recibo.docx'))) {
            unlink(public_path('Recibo.docx'));
        }

        $objWriter->save('Recibo.docx');
        echo 200;
        return 200;
    }

    public function crearRecibo2($nombreCliente = '', $direccionCliente = '', $referenciaCliente = '', $cedulaCliente = '', $fechaHoy = '', $fechaComp = '', $abono = '', $saldo = '', $total = '', $fechaDev = '', $facturaNumero = '', $telefonoCliente = '', $celularCliente = '', $productosRecibo = NULL) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('factura.docx'));
        $my_template->setValue('nombreCliente', $nombreCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('referenciaCliente', $referenciaCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('cedulaCliente', $cedulaCliente);
        $my_template->setValue('fechaHoy', $fechaHoy);
        $my_template->setValue('fechaComp', $fechaComp);
        $my_template->setValue('abono', $abono);
        $my_template->setValue('saldo', $saldo);
        $my_template->setValue('total', $total);
        $my_template->setValue('fechaDev', $fechaDev);
        $my_template->setValue('facturaNumero', $facturaNumero);
        $my_template->setValue('telefonoCliente', $telefonoCliente);
        $my_template->setValue('celularCliente', $celularCliente);
        $conteoProductos = count($productosRecibo);

        if ($conteoProductos <= 15) {
            $j = 1;
            foreach ($productosRecibo as $prod) {
                $id = $prod[0];
                $buscadoProducto = Producto::find($id);
                $my_template->setValue('p' . $j, $buscadoProducto->nombre . '- (' . $buscadoProducto->referencia . ')');
                $my_template->setValue('v' . $j, $buscadoProducto->valor);
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
            $my_template->saveAs(public_path('ReciboGenerado.docx'));

            echo 200;
        } catch (Exception $e) {
            //handle exception
        }
    }

    
     public function crearRecibo3($nombreCliente = '', $direccionCliente = '', $referenciaCliente = '', $cedulaCliente = '', $fechaHoy = '', $fechaComp = '', $abono = '', $saldo = '', $total = '', $fechaDev = '', $facturaNumero = '', $telefonoCliente = '', $celularCliente = '', $productosRecibo = NULL) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('factura2.docx'));
        $my_template->setValue('nombreCliente', $nombreCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('referenciaCliente', $referenciaCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('cedulaCliente', $cedulaCliente);
        $my_template->setValue('fechaHoy', $fechaHoy);
        $my_template->setValue('fechaComp', $fechaComp);
        $my_template->setValue('abono', $abono);
        $my_template->setValue('saldo', $saldo);
        $my_template->setValue('total', $total);
        $my_template->setValue('fechaDev', $fechaDev);
        $my_template->setValue('facturaNumero', $facturaNumero);
        $my_template->setValue('telefonoCliente', $telefonoCliente);
        $my_template->setValue('celularCliente', $celularCliente);
        $conteoProductos = count($productosRecibo);

        if ($conteoProductos <= 15) {
            $j = 1;
            foreach ($productosRecibo as $prod) {
                $id = $prod;
                $buscadoProducto = Producto::find($id);
                $my_template->setValue('p' . $j, $buscadoProducto->nombre . '- (' . $buscadoProducto->referencia . ')');
                $my_template->setValue('v' . $j, $buscadoProducto->valor);
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
            $my_template->saveAs(public_path('ReciboGeneradoEntrega.docx'));
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="ReciboGeneradoEntrega.docx"');
            readfile(public_path('ReciboGeneradoEntrega.docx'));
            
            return redirect('compromiso');
        } catch (Exception $e) {
            echo 'Hubo un error, comuníquese con el administrador del sistema';
        }
    }

    public function eliminar($id) {
        $compromiso = Compromiso::find($id);

        $productosCompromiso = CompromisoProducto::where('id_compromiso', $id)->get();

        if (count($productosCompromiso) >= 1 && !is_null($productosCompromiso) && isset($productosCompromiso)) {
            foreach ($productosCompromiso as $producto) {
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

    public function detalle($id_compromiso) {

        $productos = CompromisoProducto::where('id_compromiso', $id_compromiso)->get();
        return view('compromiso.detalle', compact('productos'));
    }

    public function entregar($id) {

        $productos = CompromisoProducto::where('id_compromiso', $id)->get();
        $compromiso = Compromiso::find($id);
        $factura = Factura::find($compromiso->id_factura);
        return view('compromiso.entrega', compact('productos', 'compromiso', 'factura'));
    }

    public function penalizar($id) {

        $productos = CompromisoProducto::where('id_compromiso', $id)->get();
        $compromiso = Compromiso::find($id);
        $factura = Factura::find($compromiso->id_factura);
        return view('compromiso.penalizar', compact('productos', 'compromiso', 'factura'));
    }

    public function devolver($id) {

        $productos = CompromisoProducto::where('id_compromiso', $id)->get();
        $compromiso = Compromiso::find($id);
        $factura = Factura::find($compromiso->id_factura);

        //calcular recargo

        $fecha_devolucionPactada = strtotime($compromiso->fecha_devolucion);

        $fechaHoy = strtotime(date('y-m-d'));
        $recargo = NULL;
        $textrecargo = 'Recargo';

        if ($fechaHoy > $fecha_devolucionPactada) {
            //calcular diferencia de dias 

            $datetime1 = date_create($compromiso->fecha_devolucion);
            $datetime2 = date_create(date('y-m-d'));
            $interval = date_diff($datetime1, $datetime2);

            $interval = $interval->format('%a');

            $porcentaje = ( intval($factura->valor) * 5) / 100;

            $recargo = $porcentaje * $interval;
            $textrecargo = 'Recargo por: ' . $interval . ' días.';
        }


        return view('compromiso.devolucion', compact('productos', 'compromiso', 'factura', 'recargo', 'textrecargo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPenalizar(Request $request) {
        $compromisoModificar = Compromiso::find($request->id_compromiso);
        $compromisoModificar->estado = 'Penalizado';
        $compromisoModificar->save();
        $facturaModificar = Factura::find($request->id_factura);
        $valor_devolucion = intval($request->input('valor_devolucion'));

        if ($valor_devolucion == 0) {
            return redirect('compromiso');
        }
        $persona = Persona::where('cedula', $compromisoModificar->cedula)->first();
        $salida = new Salida;
        $salida->concepto = 'Penalización compromiso con factura no: ' . $facturaModificar->numero_factura;
        $salida->valor = $valor_devolucion;
        $salida->nombre_persona = $persona->name;
        $salida->identificacion = $persona->cedula;

        if ($salida->save()) {
            return redirect('compromiso');
        }
    }

    public function ajustar($id_compromiso = NULL, $option = NULL) {

        $compromisoModificar = Compromiso::find($id_compromiso);
        if ($option == 'a') {
            $compromisoModificar->ajustado = 1;
            $compromisoModificar->save();
            return 200;
        } else if ($option == 'd') {
            $compromisoModificar->ajustado = 0;
            $compromisoModificar->save();
            return 200;
        }
    }

    public function postEntregar(Request $request) {

        $facturaModificar = Factura::find($request->id_factura);
        $facturaModificar->estado = 'Pagada';
        $facturaModificar->fecha_pago = date('y-m-d');
        $facturaModificar->metodo_pago_saldo = $request->tipo_pago;
        $facturaModificar->save();  
        
       
        $compromisoModificar = Compromiso::find($request->id_compromiso);
        $productosCompromiso = CompromisoProducto::where('id_compromiso', $compromisoModificar->id_compromiso)->get()->pluck('id_producto');
        

        
        //cliente del compromiso
        
        $clienteCompromiso = Persona::where('cedula', $compromisoModificar->cedula )->first();
        
        $garantia = new Garantia;
        $garantia->tipo_garantia = $request->tipo_garantia;
        if (!is_null($request->valor_garantia)) {
            $garantia->valor = $request->valor_garantia;
            $garantia->fecha_pago = date('y-m-d');
        }
        $garantia->save();

        $compromisoModificar->id_garantia = $garantia->id_garantia;
        $compromisoModificar->estado = 'Entregado';
        if ($compromisoModificar->save()) {

            //datos para word
            $nombreCliente = '';
            $direccionCliente = '';
            $referenciaCliente = '';
            $cedulaCliente = '';
            $fechaHoy = date('Y-m-d');
            $fechaComp = '';
            $abono2 = '';
            $saldo2 = '';
            $total2 = '';
            $fechaDev = '';
            $facturaNumero2 = '';
            $celularCliente = '';
            $telefonoCliente = '';

            $productosRecibo = array();

            if (isset($productosCompromiso)) {
                $productosRecibo = $productosCompromiso;
            }
            if (isset($clienteCompromiso->name)) {
                $nombreCliente = $clienteCompromiso->name;
            }
            if (isset($clienteCompromiso->celular)) {
                $celularCliente = $clienteCompromiso->celular;
            }
            if (isset($clienteCompromiso->telefono)) {
                $telefonoCliente = $clienteCompromiso->telefono;
            }
            if (isset($clienteCompromiso->direccion)) {
                $direccionCliente = $clienteCompromiso->direccion;
            }
            if (isset($clienteCompromiso->direccion)) {
                $direccionCliente = $clienteCompromiso->direccion;
            }
            if (isset($clienteCompromiso->referencia_nombre)) {
                $referenciaCliente = $clienteCompromiso->referencia_nombre;
            }
            if (isset($clienteCompromiso->cedula)) {
                $cedulaCliente = $clienteCompromiso->cedula;
            }

            if (isset($compromisoModificar->fecha_compromiso)) {
                $fechaComp = $compromisoModificar->fecha_compromiso;
            }
            if (isset($compromisoModificar->fecha_devolucion)) {
                $fechaDev = $compromisoModificar->fecha_devolucion;
            }
            if (isset($facturaModificar->abono)) {
                $abono2 = $facturaModificar->abono;
            }
            if (isset($facturaModificar->saldo)) {
                $saldo2 = $facturaModificar->saldo;
            }
            if (isset($facturaModificar->numero_factura)) {
                $facturaNumero2 = $facturaModificar->numero_factura;
            }
            if (isset($facturaModificar->saldo) && isset($facturaModificar->abono)) {
                $total2 = intval($facturaModificar->abono) + intval($facturaModificar->saldo);
            }
           

            $this->crearRecibo3($nombreCliente, $direccionCliente, $referenciaCliente, $cedulaCliente, $fechaHoy, $fechaComp, $abono2, $saldo2, $total2, $fechaDev, $facturaNumero2, $telefonoCliente, $celularCliente, $productosRecibo);
//                      
            return redirect('compromiso');
        }
    }

    public function postDevolucion(Request $request) {

        $compromisoModificar = Compromiso::find($request->id_compromiso);
        $compromisoModificar->estado = 'Devuelto';
        $compromisoModificar->buen_estado = $request->malas_condiciones;
        if (isset($request->condiciones) && !is_null($request->condiciones)) {
            $compromisoModificar->condiciones_entrega = $request->condiciones;
        }
        if (isset($request->valor_danio) && !is_null($request->valor_danio)) {
            $nuevaentrada = new Entrada;
            $nuevaentrada->concepto = 'Pago por daños de compromiso con factura No ' . $compromisoModificar->factura->numero_factura;
            $nuevaentrada->valor = $request->valor_danio;
            $nuevaentrada->tipo = 'Daños';
            $nuevaentrada->save();
        }

        if (!isset($request->anular) && is_null($request->anular)) {
            if (!is_null($request->recargo)) {
                $nuevaentrada = new Entrada;
                $nuevaentrada->concepto = 'Recargo por demora de entrega de compromiso con factura No  ' . $compromisoModificar->factura->numero_factura;
                $nuevaentrada->valor = $request->recargo;
                $nuevaentrada->tipo = 'Recargo';
                $nuevaentrada->save();
            }
        }

        //ver si tiene una garantía
        $valorGarantiaSalida = 0;
        if (isset($compromisoModificar->id_garantia)) {
            $garantiaQuitar = Garantia::find($compromisoModificar->id_garantia);
            if ($garantiaQuitar->tipo_garantia == 'dinero') {
                if (isset($garantiaQuitar->valor)) {
                    $valorGarantiaSalida = $garantiaQuitar->valor;
                }
            }

            // poner la garantia en estado 0
            $garantiaQuitar->estado = 0;
            $garantiaQuitar->save();
        }

        if ($valorGarantiaSalida != 0) {

            $cliente = Persona::where('cedula', $compromisoModificar->cedula)->first();

            $salida = new Salida;
            $salida->concepto = 'Reintegro de garantía al cliente';
            $salida->valor = $valorGarantiaSalida;
            $salida->nombre_persona = $cliente->name;
            $salida->identificacion = $cliente->cedula;
            $salida->save();
        }


        if ($compromisoModificar->save()) {
            return redirect('compromiso');
        }
    }

}
